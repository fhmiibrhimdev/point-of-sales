<?php

namespace App\Http\Livewire\Laporan;

use Carbon\Carbon;
use App\Models\User;
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

        $users      = User::select('id', 'name')->get();

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
        return view('livewire.kartu-stock.kartu-stock', compact('data', 'barangs', 'users'))
            ->extends('layouts.apps', ['title' => 'Kartu Stock']);
    }

    public function mount()
    {
        $this->filter_id_barang = 0;
        $this->filter_dari_tanggal = Carbon::now()->startOfMonth()->format('Y-m-d 00:00');
        $this->filter_sampai_tanggal = Carbon::now()->endOfMonth()->format('Y-m-d 23:59');
    }

    public function exportPDF($id_barang, $dari_tanggal, $sampai_tanggal)
    {
        $data = Persediaan::select('tanggal', 'nama_barang', 'persediaan.keterangan', 'status', 'qty', DB::raw("SUM(CASE WHEN status = 'Out' THEN -qty ELSE qty END) OVER(ORDER BY tanggal ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS balancing"))
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->where('id_barang', $id_barang)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->orderBy('tanggal', 'ASC')
                ->get();

        return view('livewire.pdf.kartu-stock', ['data' => $data, 'no' => 1, 'dari_tanggal' => $dari_tanggal, 'sampai_tanggal' => $sampai_tanggal]);
    }

    public function exportEXCEL($id_barang, $dari_tanggal, $sampai_tanggal)
    {
        $data = Persediaan::select('tanggal', 'nama_barang', 'persediaan.keterangan', 'status', 'qty', DB::raw("SUM(CASE WHEN status = 'Out' THEN -qty ELSE qty END) OVER(ORDER BY tanggal ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS balancing"))
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->where('id_barang', $id_barang)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->orderBy('tanggal', 'ASC')
                ->get()
                ->toArray();

        $fileName = "INV/" . date("Ymd") . "/KARTU-STOCK" . ".xls";
        if ($data) 
        {
            function filterData(&$str)
            {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-Type: application/vnd.ms-excel");
            $flag = false;
            foreach ($data as $row) 
            {
                if (!$flag) 
                {
                    echo implode("\t", array_keys($row)) . "\n";
                    $flag = true;
                }
                array_walk($row, __NAMESPACE__ . '\filterData');
                echo implode("\t", array_values($row)) . "\n";
            }
            exit;
        }
    }
}
