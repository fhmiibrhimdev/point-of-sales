<?php

namespace App\Http\Livewire\Laporan;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\DataBarang;
use App\Models\Persediaan;
use Illuminate\Support\Facades\DB;

class BarangOpname extends Component
{
    public $id_user, $id_barang;
    public $dari_tanggal, $sampai_tanggal;

    public function render()
    {
        if ( $this->id_user == 0 && $this->id_barang == 0 )
        {
            $data = Persediaan::select('persediaan.*', 'data_barang.nama_barang' ,'users.name')
                        ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                        ->join('users', 'users.id', 'persediaan.id_user')
                        ->whereBetween('persediaan.created_at', [$this->dari_tanggal, $this->sampai_tanggal])
                        ->where('persediaan.opname', 'yes')
                        ->orderBy('persediaan.id', 'DESC')
                        ->get();

        } else if ( $this->id_user == 0 && $this->id_barang > 0 )
        {
            $data = Persediaan::select('persediaan.*', 'data_barang.nama_barang' ,'users.name')
                        ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                        ->join('users', 'users.id', 'persediaan.id_user')
                        ->where('persediaan.opname', 'yes')
                        ->where('data_barang.id', $this->id_barang)
                        ->whereBetween('tanggal', [$this->dari_tanggal, $this->sampai_tanggal])
                        ->orderBy('persediaan.id', 'DESC')
                        ->get();
        } else if ( $this->id_user > 0 && $this->id_barang == 0 )
        {
            $data = Persediaan::select('persediaan.*', 'data_barang.nama_barang' ,'users.name')
                        ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                        ->join('users', 'users.id', 'persediaan.id_user')
                        ->where('persediaan.opname', 'yes')
                        ->where('persediaan.id_user', $this->id_user)
                        ->whereBetween('tanggal', [$this->dari_tanggal, $this->sampai_tanggal])
                        ->orderBy('persediaan.id', 'DESC')
                        ->get();
        } else if ( $this->id_user > 0 && $this->id_barang > 0 )
        {
            $data = Persediaan::select('persediaan.*', 'data_barang.nama_barang' ,'users.name')
                        ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                        ->join('users', 'users.id', 'persediaan.id_user')
                        ->where('persediaan.opname', 'yes')
                        ->where('persediaan.id_user', $this->id_user)
                        ->where('data_barang.id', $this->id_barang)
                        ->whereBetween('tanggal', [$this->dari_tanggal, $this->sampai_tanggal])
                        ->orderBy('persediaan.id', 'DESC')
                        ->get();
        }

        $users = User::select('id', 'name')->get();
        $barangs = DataBarang::select('id', 'nama_barang')->get();

        return view('livewire.laporan.barang-opname', compact('data', 'users', 'barangs'))
            ->extends('layouts.apps', ['title' => 'Laporan Inventory - Barang Opname']);
    }

    public function mount()
    {
        $this->id_user          = '0';
        $this->id_barang        = '0';
        $this->dari_tanggal     = Carbon::now()->startOfMonth()->toDateTimeLocalString();
        $this->sampai_tanggal   = Carbon::now()->endOfMonth()->toDateTimeLocalString();
    }

    public function exportExcel($id_user, $id_barang, $dari_tanggal, $sampai_tanggal)
    {
        if ($id_barang == 0 && $id_barang == 0) 
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d-%m-%y') as tanggal"), 'data_barang.nama_barang', 'persediaan.buku', 'persediaan.fisik', 'persediaan.selisih', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get()
                ->toArray();
        } else if ($id_barang > 0 && $id_user == 0) 
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d-%m-%y') as tanggal"), 'data_barang.nama_barang', 'persediaan.buku', 'persediaan.fisik', 'persediaan.selisih', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->where('data_barang.id', $id_barang)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get()
                ->toArray();
        } else if ($id_barang == 0 && $id_user > 0)
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d-%m-%y') as tanggal"), 'data_barang.nama_barang', 'persediaan.buku', 'persediaan.fisik', 'persediaan.selisih', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->where('data_barang.id_user', $id_user)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get()
                ->toArray();
        } else if ($id_barang > 0 && $id_user > 0)
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d-%m-%y') as tanggal"), 'data_barang.nama_barang', 'persediaan.buku', 'persediaan.fisik', 'persediaan.selisih', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->where('data_barang.id', $id_barang)
                ->where('data_barang.id_user', $id_user)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get()
                ->toArray();
        }
        
        $fileName = "INV/" . date("Ymd") . "/BARANG-OPNAME" . ".xls";
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

    public function exportPDF($id_user, $id_barang, $dari_tanggal, $sampai_tanggal)
    {
        if ($id_barang == 0 && $id_barang == 0) 
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal"), 'persediaan.*', 'data_barang.nama_barang', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get();
        } else if ($id_barang > 0 && $id_user == 0) 
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal"), 'persediaan.*', 'data_barang.nama_barang', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->where('data_barang.id', $id_barang)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get();
        } else if ($id_barang == 0 && $id_user > 0)
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal"), 'persediaan.*', 'data_barang.nama_barang', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->where('data_barang.id_user', $id_user)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get();
        } else if ($id_barang > 0 && $id_user > 0)
        {
            $data = Persediaan::select(DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal"), 'persediaan.*', 'data_barang.nama_barang', 'users.name')
                ->join('data_barang', 'data_barang.id', 'persediaan.id_barang')
                ->join('users', 'users.id', 'persediaan.id_user')
                ->where('data_barang.id', $id_barang)
                ->where('data_barang.id_user', $id_user)
                ->whereBetween('persediaan.created_at', [$dari_tanggal, $sampai_tanggal])
                ->where('persediaan.opname', 'yes')
                ->orderBy('persediaan.id', 'DESC')
                ->get();
        }

        return view('livewire.pdf.barang-opname', ['data' => $data, 'no' => 1, 'dari_tanggal' => $dari_tanggal, 'sampai_tanggal' => $sampai_tanggal]);

        // $pdf = Pdf::loadview('livewire.laporan.saldo-awal-item', ['data' => $data, 'no' => 1]);
        // return $pdf->download('laporan-pegawai.pdf');
    }


}
