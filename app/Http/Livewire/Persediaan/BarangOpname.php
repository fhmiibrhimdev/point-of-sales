<?php

namespace App\Http\Livewire\Persediaan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\DataBarang;
use App\Models\Persediaan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BarangOpname extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $listeners = [
        'delete'
    ];
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'tanggal'       => 'required',
        'id_barang'     => 'required',
        'keterangan'    => 'required',
        'buku'          => 'required',
        'fisik'         => 'required',
    ];
    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $id_user, $tanggal, $id_barang, $qty, $keterangan, $status, $buku, $fisik, $selisih;
    public $filter_id_barang, $filter_dari_tanggal, $filter_sampai_tanggal;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;

        $barangs    = DataBarang::select('id', 'nama_barang')
                        ->get();

        if ($this->filter_id_barang == 0) 
        {
            $data = Persediaan::select('persediaan.*', 'data_barang.nama_barang')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->where(function($query) use ($search) {
                    $query->where('tanggal', 'LIKE', $search);
                    $query->orWhere('nama_barang', 'LIKE', $search);
                })
                ->whereBetween('persediaan.created_at', [$this->filter_dari_tanggal, $this->filter_sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->paginate($lengthData);

        } else if ($this->filter_id_barang > 0) 
        {
            $data = Persediaan::select('persediaan.*', 'data_barang.nama_barang')
            ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
            ->where(function($query) use ($search) {
                $query->where('tanggal', 'LIKE', $search);
                $query->orWhere('nama_barang', 'LIKE', $search);
            })
            ->where('data_barang.id', $this->filter_id_barang)
            ->whereBetween('persediaan.created_at', [$this->filter_dari_tanggal, $this->filter_sampai_tanggal])
            ->where('persediaan.opname', 'yes')
            ->orderBy('persediaan.id', 'DESC')
            ->paginate($lengthData);
        }

        

        return view('livewire.persediaan.barang-opname', compact('data', 'barangs'))
        ->extends('layouts.apps', ['title' => 'Stok Opname']);
    }

    public function mount()
    {
        $this->id_user                  = 0;
        $this->tanggal                  = date('Y-m-d H:i');
        $this->id_barang                = DataBarang::min('id');
        $this->keterangan               = '[Opname]';
        $this->filter_dari_tanggal      = Carbon::now()->startOfMonth()->toDateTimeLocalString();
        $this->filter_sampai_tanggal    = Carbon::now()->endOfMonth()->toDateTimeLocalString();
        $this->filter_id_barang         = 0;

        if ($this->id_barang) 
        {
            $this->buku = DataBarang::select('stock')
                            ->where('id', $this->id_barang)
                            ->first()
                            ->stock;
            $this->fisik = DataBarang::select('stock')
                            ->where('id', $this->id_barang)
                            ->first()
                            ->stock;
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    private function alertShow($type, $title, $text, $onConfirmed, $showCancelButton)
    {
        $this->alert($type, $title, [
            'position'          => 'center',
            'timer'             => '3000',
            'toast'             => false,
            'text'              => $text,
            'showConfirmButton' => true,
            'onConfirmed'       => $onConfirmed,
            'showCancelButton'  => $showCancelButton,
            'onDismissed'       => '',
        ]);
        $this->resetInputFields();
        $this->emit('dataStore');
    }

    private function resetInputFields()
    {
        $this->id_barang    = DataBarang::min('id');
        $this->keterangan   = 'Opname';

        if ($this->id_barang) 
        {

            $this->buku = DataBarang::select('stock')
                            ->where('id', $this->id_barang)
                            ->first()
                            ->stock;
            $this->fisik = DataBarang::select('stock')
                            ->where('id', $this->id_barang)
                            ->first()
                            ->stock;
        }
    }

    private function alertStockMinus()
    {
        $this->alertShow(
            'error', 
            'Gagal!', 
            'Stock minus tidak diperbolehkan!', 
            '', 
            false
        );
        return false;
    }

    public function store()
    {
        $this->validate();

        $fisik      = $this->fisik;
        $buku       = $this->buku;

        $selisih    = $fisik - $buku;

        if ($selisih > 0) 
        {
            $status = 'In';
        } else 
        {
            $status = 'Out';
        }

        if ($fisik <= 0)
        {
            $this->alertStockMinus();
            return false;
        }

        Persediaan::create([
            'id_user'       => Auth::user()->id,
            'tanggal'       => $this->tanggal,
            'id_barang'     => $this->id_barang,
            'qty'           => abs($selisih),
            'keterangan'    => $this->keterangan,
            'buku'          => $this->buku,
            'fisik'         => $this->fisik,
            'selisih'       => $selisih,
            'opname'        => 'yes',
            'status'        => $status,
        ]);

        DataBarang::where('id', $this->id_barang)
            ->update([
                'stock' => $this->fisik
            ]);
        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil ditambahkan', 
            '', 
            false
        );
    }

    public function deleteConfirm($id)
    {
        $this->idRemoved = $id;
        $this->alertShow(
            'warning', 
            'Apa anda yakin?', 
            'Jika anda menghapus data tersebut, data tidak bisa dikembalikan!', 
            'delete', 
            true
        );
    }

    public function delete()
    {
        $dataPersediaan = Persediaan::select('id_barang', 'selisih')
                            ->where('id', $this->idRemoved)
                            ->first();
        $id_barang      = $dataPersediaan->id_barang;
        $selisih        = $dataPersediaan->selisih;
        $stock_barang   = DataBarang::select('stock')
                            ->where('id', $id_barang)
                            ->first()
                            ->stock;
        $stock_sekarang = $stock_barang - $selisih;
        DataBarang::where('id', $id_barang)
            ->update([
                'stock'     => $stock_sekarang
            ]);

        $data = Persediaan::findOrFail($this->idRemoved);
        $data->delete();
        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil dihapus', 
            '', 
            false
        );
    }

    public function updatedIdBarang($id_barang) {
        if ($id_barang) 
        {
            $this->buku = DataBarang::select('stock')
                            ->where('id', $this->id_barang)
                            ->first()
                            ->stock;
            $this->updateValueBuku($this->buku);
        }
    }

    public function updateValueBuku($value)
    {
        $this->fisik = $value;
    }

}
