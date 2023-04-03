<?php

namespace App\Http\Livewire\MasterData;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Satuan as ModelsSatuan;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Satuan extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $listeners = [
        'delete'
    ];
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'nama_satuan'     => 'required',
    ];

    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $nama_satuan;

    public function render()
    {
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;

        $data = ModelsSatuan::where('nama_satuan', 'LIKE', $search)
                    ->orderBy('id', 'ASC')
                    ->paginate($lengthData);

        return view('livewire.master-data.satuan', compact('data'))
        ->extends('layouts.apps', ['title' => 'Kategori']);
    }

    public function mount()
    {
        $this->nama_satuan    = '';
    }
    
    private function resetInputFields()
    {
        $this->nama_satuan    = '';
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

        ModelsSatuan::create([
            'nama_satuan'     => strtoupper($this->nama_satuan),
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
        $data = ModelsSatuan::where('id', $id)->first();
        $this->dataId           = $id;
        $this->nama_satuan    = $data->nama_satuan;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            ModelsSatuan::findOrFail($this->dataId)->update([
                'nama_satuan'     => strtoupper($this->nama_satuan),
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
        ModelsSatuan::findOrFail($this->idRemoved)->delete();
        $this->alertShow(
            'success', 
            'Berhasil', 
            'Data berhasil dihapus', 
            '', 
            false
        );
    }
}
