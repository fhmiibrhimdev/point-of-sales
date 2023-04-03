<?php

namespace App\Http\Livewire\Persediaan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\DataBarang;
use App\Models\Persediaan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BarangKeluar extends Component
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
        'qty'           => 'required',
    ];

    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $tanggal, $id_barang, $qty, $keterangan, $id_user;
    public $filter_dari_tanggal, $filter_sampai_tanggal, $filter_id_barang;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;
        $barangs    = DataBarang::select('id', 'nama_barang')
                ->get();
        $qty_barang = DataBarang::where('id', $this->id_barang)->first()->stock;

        if ($this->filter_id_barang == 0) 
        {
            $data = Persediaan::select('persediaan.id', 'tanggal', 'nama_barang', 'qty', 'keterangan')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->where(function($query) use ($search) {
                    $query->where('tanggal', 'LIKE', $search);
                    $query->orWhere('nama_barang', 'LIKE', $search);
                })
                ->whereBetween('persediaan.created_at', [$this->filter_dari_tanggal, $this->filter_sampai_tanggal])
                ->where('persediaan.status', 'Out')
                ->where('persediaan.opname', 'no')
                ->orderBy('persediaan.id', 'DESC')
                ->paginate($lengthData);

        } else if ($this->filter_id_barang > 0) 
        {
            $data = Persediaan::select('persediaan.id', 'tanggal', 'nama_barang', 'qty', 'keterangan')
            ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
            ->where(function($query) use ($search) {
                $query->where('tanggal', 'LIKE', $search);
                $query->orWhere('nama_barang', 'LIKE', $search);
            })
            ->where('data_barang.id', $this->filter_id_barang)
            ->whereBetween('persediaan.created_at', [$this->filter_dari_tanggal, $this->filter_sampai_tanggal])
            ->where('persediaan.status', 'Out')
            ->where('persediaan.opname', 'no')
            ->orderBy('persediaan.id', 'DESC')
            ->paginate($lengthData);
        }

        return view('livewire.persediaan.barang-keluar', compact('data', 'barangs', 'qty_barang'))
        ->extends('layouts.apps', ['title' => 'Stok Keluar']);
    }

    public function mount()
    {
        $this->id_user                  = 0;
        $this->tanggal                  = date('Y-m-d H:i');
        $this->id_barang                = DataBarang::min('id');
        $this->qty                      = '1';
        $this->keterangan               = '[Out]';
        $this->filter_dari_tanggal      = Carbon::now()->startOfMonth()->toDateTimeLocalString();
        $this->filter_sampai_tanggal    = Carbon::now()->endOfMonth()->toDateTimeLocalString();
        $this->filter_id_barang         = 0;
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
        $this->tanggal      = date('Y-m-d H:i');
        $this->keterangan   = '[Out]';
        $this->qty          = '1';
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
        $stock_terakhir_barang  = DataBarang::where('id', $this->id_barang)->first()->stock;
        $kurang_stock           = $stock_terakhir_barang - $this->qty;

        if ($kurang_stock < 0) // jika stock pengurangan minus
        { 
            $this->alertStockMinus();
        } else // jika stock pengurangan tidak minus
        { 
            Persediaan::create([
                'id_user'       => Auth::user()->id,
                'tanggal'       => $this->tanggal,
                'id_barang'     => $this->id_barang,
                'qty'           => $this->qty,
                'keterangan'    => $this->keterangan,
                'status'        => 'Out',
            ]);
            DataBarang::where('id', $this->id_barang)
                ->update(array(
                    'stock' => $kurang_stock
                ));
            $this->alertShow(
                'success', 
                'Berhasil', 
                'Data berhasil ditambahkan', 
                '', 
                false
            );
        }
    }

    public function edit($id)
    {
        $this->updateMode   = true;
        $data = Persediaan::where('id', $id)->first();
        $this->dataId       = $id;
        $this->tanggal      = $data->tanggal;
        $this->id_barang    = $data->id_barang;
        $this->qty          = $data->qty;
        $this->keterangan   = $data->keterangan;
    }

    private function logicStock($total_stock_sekarang, $qty_baru, $qty_lama, $id_barang)
    {
        $total          = $qty_lama - $qty_baru; // 3 - 10 = -7
        $total_stock    = $total_stock_sekarang + $total; // 5 + (-7) = -2
        if ($total_stock < 0) { // jika stock minus
            $this->alertStockMinus();
        } else { // jika stock tidak minus
            DataBarang::where('id', $id_barang)
                ->update(array(
                    'stock' => $total_stock
                ));
            if ($this->dataId) {
                $data = Persediaan::findOrFail($this->dataId);
                $data->update([
                    'id_user'       => Auth::user()->id,
                    'tanggal'       => $this->tanggal,
                    'qty'           => $this->qty,
                    'keterangan'    => $this->keterangan,
                    'status'        => 'Out',
                ]);
                $this->alertShow(
                    'success', 
                    'Berhasil', 
                    'Data berhasil diubah', 
                    '', 
                    false
                );
            }
        }
    }

    public function update()
    {
        $this->validate();
        $id_barang              = Persediaan::where('id', $this->dataId)
                                    ->first()
                                    ->id_barang; // ambil id barang
        $total_stock_sekarang   = DataBarang::where('id', $id_barang)
                                    ->first()
                                    ->stock; // Stock di tb data_barang : 5
        $qty_lama               = Persediaan::where('id', $this->dataId)
                                    ->first()
                                    ->qty; // Quantity Lama : 3
        $qty_baru               = $this->qty; // Quantity Baru : 10
        switch (true) {
            case ((int)$qty_baru > (int)$qty_lama):
            case ((int)$qty_lama > (int)$qty_baru):
            case ((int)$qty_lama == (int)$qty_baru):
                $this->logicStock($total_stock_sekarang, $qty_baru, $qty_lama, $id_barang);
                break;
            default:
                break;
        }
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
        $qty_terakhir   = Persediaan::where('id', $this->idRemoved)->first()->qty; // 7
        $id_barang      = Persediaan::where('id', $this->idRemoved)->first()->id_barang;
        $stock_terakhir = DataBarang::where('id', $id_barang)->first()->stock; // Stock : 13

        $total_stock    = $stock_terakhir + $qty_terakhir;

        DataBarang::where('id', $id_barang)
            ->update(array(
                'stock' => $total_stock
            ));

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
}
