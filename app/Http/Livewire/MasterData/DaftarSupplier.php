<?php

namespace App\Http\Livewire\MasterData;

use App\Models\DaftarSupplier as ModelsDaftarSupplier;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DaftarSupplier extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $listeners = [
        'delete'
    ];
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'nama_supplier'     => 'required',
        'hp_supplier'       => 'required',
    ];

    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $nama_supplier, $hp_supplier, $alamat_supplier, $deskripsi_supplier;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;

        $data = ModelsDaftarSupplier::where('nama_supplier', 'LIKE', $search)
            ->orWhere('hp_supplier', 'LIKE', $search)
            ->orWhere('alamat_supplier', 'LIKE', $search)
            ->orWhere('deskripsi_supplier', 'LIKE', $search)
            ->orderBy('id', 'ASC')
            ->paginate($lengthData);

        return view('livewire.master-data.daftar-supplier', compact('data'))
        ->extends('layouts.apps', ['title' => 'Daftar Supplier']);
    }

    public function mount()
    {
        $this->nama_supplier        = '';
        $this->hp_supplier          = '62';
        $this->alamat_supplier      = '-';
        $this->deskripsi_supplier   = '-';
    }
    
    private function resetInputFields()
    {
        $this->nama_supplier        = '';
        $this->hp_supplier          = '62';
        $this->alamat_supplier      = '-';
        $this->deskripsi_supplier   = '-';
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

        
        ModelsDaftarSupplier::create(
            $this->only(['nama_supplier', 'hp_supplier', 'alamat_supplier', 'deskripsi_supplier'])
        );

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
        $data = ModelsDaftarSupplier::where('id', $id)->first();
        $this->dataId               = $id;
        $this->nama_supplier        = $data->nama_supplier;
        $this->hp_supplier          = $data->hp_supplier;
        $this->alamat_supplier      = $data->alamat_supplier;
        $this->deskripsi_supplier   = $data->deskripsi_supplier;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            ModelsDaftarSupplier::findOrFail($this->dataId)->update([
                'nama_supplier'         => $this->nama_supplier,
                'hp_supplier'           => $this->hp_supplier,
                'alamat_supplier'       => $this->alamat_supplier,
                'deskripsi_supplier'    => $this->deskripsi_supplier,
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
        ModelsDaftarSupplier::findOrFail($this->idRemoved)->delete();
        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil dihapus', 
            '', 
            false
        );
    }
}
