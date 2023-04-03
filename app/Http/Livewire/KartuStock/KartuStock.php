<?php

namespace App\Http\Livewire\KartuStock;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\DataBarang;
use App\Models\Persediaan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class KartuStock extends Component
{
    use WithPagination;
    public $search;
    public $filter_id_barang;
    public $filter_dari_tanggal, $filter_sampai_tanggal;
    public $lengthData  = 10;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;

        $barangs    = DataBarang::select('id', 'nama_barang')
                        ->get();

        if ($this->filter_id_barang == 0) {
            $data = '';
        } else if ($this->filter_id_barang > 0) {
            $data = Persediaan::select('tanggal', 'nama_barang', 'persediaan.keterangan', 'status', 'qty', DB::raw("SUM(CASE WHEN status = 'Out' THEN -qty ELSE qty END) OVER(ORDER BY tanggal ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS balancing"))
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->where(function ($query) use ($search) {
                    $query->where('tanggal', 'LIKE', $search);
                    $query->orWhere('persediaan.keterangan', 'LIKE', $search);
                    $query->orWhere('status', 'LIKE', $search);
                    $query->orWhere('qty', 'LIKE', $search);
                })
                ->where('id_barang', $this->filter_id_barang)
                ->whereBetween('persediaan.created_at', [$this->filter_dari_tanggal, $this->filter_sampai_tanggal])
                ->orderBy('tanggal', 'ASC')
                ->paginate($lengthData);
        }
        return view('livewire.kartu-stock.kartu-stock', compact('data', 'barangs'))
            ->extends('layouts.apps', ['title' => 'Kartu Stock']);
    }

    public function mount()
    {
        $this->filter_id_barang = 0;
        $this->filter_dari_tanggal = Carbon::now()->startOfMonth()->format('Y-m-d 00:00');
        $this->filter_sampai_tanggal = Carbon::now()->endOfMonth()->format('Y-m-d 23:59');
    }
}
