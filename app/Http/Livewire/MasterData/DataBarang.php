<?php

namespace App\Http\Livewire\MasterData;

use App\Models\Satuan;
use Livewire\Component;
use App\Models\Kategori;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\DataBarang as ModelsDataBarang;

class DataBarang extends Component
{
    use LivewireAlert;
    use WithPagination;
    use WithFileUploads;

    protected $listeners = [
        'delete'
    ];
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'nama_barang'   => 'required',
        'id_satuan'     => 'required',
        'id_kategori'   => 'required',
        'harga'         => 'required',
        'gambar'        => 'nullable|mimes:png,jpg,jpeg,gif,svg|max:4096',
    ];

    public $search;
    public $lengthData  = 10;
    public $updateMode  = false;
    public $idRemoved   = NULL;
    public $dataId      = NULL;
    public $gambar, $kode_barang, $nama_barang, $id_satuan, $id_kategori, $harga, $stock, $gambarLama;

    public function render()
    {
        $this->generateKode();
        $search     = '%'.$this->search.'%';
        $lengthData = $this->lengthData;

        $satuans    = Satuan::select('id', 'nama_satuan')->get();
        $kategoris  = Kategori::select('id', 'nama_kategori')->get();

        $data = ModelsDataBarang::select('data_barang.id', 'gambar', 'kode_barang', 'nama_barang', 'nama_satuan', 'nama_kategori', 'harga', 'stock')
            ->join('satuan','satuan.id', 'data_barang.id_satuan')
            ->join('kategori','kategori.id', 'data_barang.id_kategori')
            ->where(function($query) use ($search) {
                $query->where('kode_barang', 'LIKE', $search);
                $query->orWhere('nama_barang', 'LIKE', $search);
                $query->orWhere('nama_satuan', 'LIKE', $search);
                $query->orWhere('nama_kategori', 'LIKE', $search);
                $query->orWhere('harga', 'LIKE', $search);
                $query->orWhere('stock', 'LIKE', $search);
            })
            ->orderBy('data_barang.id', 'ASC')
            ->paginate($lengthData);

        return view('livewire.master-data.data-barang', compact('data', 'satuans', 'kategoris'))
        ->extends('layouts.apps', ['title' => 'Daftar Barang']);
    }

    private function generateKode()
    {
        $kodeBarang = ModelsDataBarang::max('kode_barang');
        $urutan = (int)substr($kodeBarang, 4, 4);
        $urutan++;
        $huruf = 'BRG';
        $kodeBarang = $huruf . sprintf("%04s", $urutan);

        $this->kode_barang = $kodeBarang;
    }

    public function mount()
    {
        $this->nama_barang      = '';
        $this->id_satuan        = Satuan::min('id');
        $this->id_kategori      = Kategori::min('id');
        $this->harga            = '0';
    }
    
    private function resetInputFields()
    {
        $this->nama_barang      = '';
        $this->harga            = '0';
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

        switch ($this->gambar) {
            case '':
                $imagePath = '';
                break;
            
            default:
                $imagePath = $this->gambar->store('images', 'public');
                break;
        }

        ModelsDataBarang::create([
            'id_user'           => Auth::user()->id,
            'gambar'            => $imagePath,
            'kode_barang'       => $this->kode_barang,
            'nama_barang'       => strtoupper($this->nama_barang),
            'id_satuan'         => $this->id_satuan,
            'id_kategori'       => $this->id_kategori,
            'harga'             => $this->harga,
            'stock'             => '0',
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
        $data = ModelsDataBarang::where('id', $id)->first();
        $this->dataId       = $id;
        $this->gambarLama   = $data->gambar;
        $this->nama_barang  = $data->nama_barang;
        $this->id_satuan    = $data->id_satuan;
        $this->id_kategori  = $data->id_kategori;
        $this->harga        = $data->harga;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            switch ($this->gambar) 
            {
                case '':
                    $imagePath = $this->gambarLama;
                    break;
                
                default:
                    $imagePath = $this->gambar->store('images', 'public');
                    switch ($this->gambarLama) 
                    {
                        case '':
                            break;
                        
                        default:
                            unlink('storage/'.$this->gambarLama);
                            break;
                    }
                    break; 
            }

            $data = ModelsDataBarang::findOrFail($this->dataId);
            $data->update([
                'id_user'           => Auth::user()->id,
                'gambar'            => $imagePath,
                'nama_barang'       => strtoupper($this->nama_barang),
                'id_satuan'         => $this->id_satuan,
                'id_kategori'       => $this->id_kategori,
                'harga'             => $this->harga,
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
        $data = ModelsDataBarang::findOrFail($this->idRemoved);
        unlink('storage/'.$data->gambar);
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
