<?php

namespace App\Http\Livewire\Laporan;

use App\Models\DaftarCustomer as ModelsDaftarCustomer;
use Livewire\Component;

class DaftarCustomer extends Component
{
    public function render()
    {
        return view('livewire.laporan.daftar-customer')
            ->extends('layouts.apps', ['title' => 'Laporan Daftar Customer']);
    }

    public function exportEXCEL()
    {
        $data = ModelsDaftarCustomer::select('nama_customer', 'hp_customer', 'alamat_customer', 'deskripsi_customer')
            ->get()
            ->toArray();

        $fileName = "INV/" . date("Ymd") . "/DAFTAR-CUSTOMER" . ".xls";
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
        $data = ModelsDaftarCustomer::select('nama_customer', 'hp_customer', 'alamat_customer', 'deskripsi_customer')
            ->get();
            
        return view('livewire.pdf.daftar-customer', ['data' => $data, 'no' => 1]);
    }
}
