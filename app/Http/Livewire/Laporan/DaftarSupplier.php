<?php

namespace App\Http\Livewire\Laporan;

use App\Models\DaftarSupplier as ModelsDaftarSupplier;
use Livewire\Component;

class DaftarSupplier extends Component
{
    public function render()
    {
        return view('livewire.laporan.daftar-supplier')
            ->extends('layouts.apps', ['title' => 'Laporan Daftar Supplier']);
    }

    public function exportEXCEL()
    {
        $data = ModelsDaftarSupplier::select('nama_supplier', 'hp_supplier', 'alamat_supplier', 'deskripsi_supplier')
            ->get()
            ->toArray();

        $fileName = "INV/" . date("Ymd") . "/DAFTAR-SUPPLIER" . ".xls";
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

    public function exportPDF()
    {
        $data = ModelsDaftarSupplier::select('nama_supplier', 'hp_supplier', 'alamat_supplier', 'deskripsi_supplier')
            ->get();
            
        return view('livewire.pdf.daftar-supplier', ['data' => $data, 'no' => 1]);
    }
}
