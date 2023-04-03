<?php

namespace App\Http\Livewire\MasterData;

use App\Models\DaftarCustomer as ModelsDaftarCustomer;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DaftarCustomer extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $listeners = [
        'delete'
    ];
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'nama_customer'     => 'required',
        'hp_customer'       => 'required',
    ];

    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $nama_customer, $hp_customer, $alamat_customer, $deskripsi_customer;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;

        $data = ModelsDaftarCustomer::where('nama_customer', 'LIKE', $search)
            ->orWhere('hp_customer', 'LIKE', $search)
            ->orWhere('alamat_customer', 'LIKE', $search)
            ->orWhere('deskripsi_customer', 'LIKE', $search)
            ->orderBy('id', 'ASC')
            ->paginate($lengthData);

        return view('livewire.master-data.daftar-customer', compact('data'))
        ->extends('layouts.apps', ['title' => 'Daftar Customer']);
    }

    public function mount()
    {
        $this->nama_customer        = '';
        $this->hp_customer          = '62';
        $this->alamat_customer      = '-';
        $this->deskripsi_customer   = '-';
    }
    
    private function resetInputFields()
    {
        $this->nama_customer        = '';
        $this->hp_customer          = '62';
        $this->alamat_customer      = '-';
        $this->deskripsi_customer   = '-';
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

        ModelsDaftarCustomer::create([
            'nama_customer'         => $this->nama_customer,
            'hp_customer'           => $this->hp_customer,
            'alamat_customer'       => $this->alamat_customer,
            'deskripsi_customer'    => $this->deskripsi_customer,
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
        $this->updateMode           = true;
        $data = ModelsDaftarCustomer::where('id', $id)->first();
        $this->dataId               = $id;
        $this->nama_customer        = $data->nama_customer;
        $this->hp_customer          = $data->hp_customer;
        $this->alamat_customer      = $data->alamat_customer;
        $this->deskripsi_customer   = $data->deskripsi_customer;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            ModelsDaftarCustomer::findOrFail($this->dataId)->update([
                'nama_customer'         => $this->nama_customer,
                'hp_customer'           => $this->hp_customer,
                'alamat_customer'       => $this->alamat_customer,
                'deskripsi_customer'    => $this->deskripsi_customer,
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
        ModelsDaftarCustomer::findOrFail($this->idRemoved)->delete();
        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil dihapus', 
            '', 
            false
        );
    }
}
