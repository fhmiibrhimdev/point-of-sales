<?php

namespace App\Http\Livewire\Transaksi;

use App\Models\User;
use Livewire\Component;
use App\Models\DataBarang;
use Livewire\WithPagination;
use App\Models\DaftarCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Transaksi as ModelsTransaksi;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Transaksi extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $listeners = [
        'delete'
    ];
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'nota'          => 'required',
        'tanggal'       => 'required',
        'id_user'       => 'required',
        'id_customer'   => 'required',
        'sub_total'     => 'required',
        'diskon'        => 'required',
        'grand_total'   => 'required',
        'cash'          => 'required',
        'kembalian'     => 'required',
        'total'         => 'required',
    ];

    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $nota, $tanggal, $id_user, $id_customer, $sub_total, $diskon, $grand_total, $cash, $kembalian, $total, $nama_kasir;
    public $addIdBarang, $addQty;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;
        $customers  = DaftarCustomer::select('id', 'nama_customer')->get();
        $barangs    = DataBarang::select('id', 'nama_barang')->get();

        return view('livewire.transaksi.transaksi', compact('customers', 'barangs'))
        ->extends('layouts.apps', ['title' => 'Transaksi Penjualan']);
    }

    public function mount()
    {
        $this->nama_kasir   = Auth::user()->name;
        $this->nota         = 'TRINV'.rand(1,999999999999);
        $this->tanggal      = date('Y-m-d H:i');
        $this->id_user      = Auth::user()->id;
        $this->id_customer  = DaftarCustomer::min('id');
        $this->sub_total    = '0';
        $this->diskon       = '0';
        $this->grand_total  = '0';
        $this->cash         = '0';
        $this->kembalian    = '0';
        $this->total        = '0';
    }
    
    private function resetInputFields()
    {
        $this->nota         = 'TRINV'.rand(1,999999999999);
        $this->tanggal      = date('Y-m-d H:i:s');
        $this->id_user      = Auth::user()->id;
        $this->id_customer  = DaftarCustomer::min('id');
        $this->sub_total    = '0';
        $this->diskon       = '0';
        $this->grand_total  = '0';
        $this->cash         = '0';
        $this->kembalian    = '0';
        $this->total        = '0';
    }

    public function cancel()
    {
        $this->updateMode       = false;
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

    public function store()
    {
        $this->validate();

        ModelsTransaksi::create([
            'nota'     => strtoupper($this->nota),
        ]);

        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil ditambahkan', 
            '', 
            false
        );
    }

    public function edit($id)
    {
        $this->updateMode       = true;
        $data = ModelsTransaksi::where('id', $id)->first();
        $this->dataId           = $id;
        $this->nota    = $data->nota;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            ModelsTransaksi::findOrFail($this->dataId)->update([
                'nota'     => strtoupper($this->nota),
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
        // dd($this->idRemoved);
    }

    public function delete()
    {
        ModelsTransaksi::findOrFail($this->idRemoved)->delete();
        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil dihapus', 
            '', 
            false
        );
    }

    public function addCart($idBarang, $qty)
    {
        $data = DataBarang::select('id', 'nama_barang', 'harga')->where('id', $idBarang)->first();

        $cart = Session::get('cart');
        $cart[] = array(
            'id_barang' => $idBarang,
            'qty'       => $qty,
            'diskon'    => 0,
            'total'     => (int)$data->harga * (int)$qty,
        );
        Session::put('cart', $cart);

        dd($cart);
    }
}
