<?php

namespace App\Imports;

use App\Models\PemotonganPajak as ModelsPemotonganPajak;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PemotonganPajak implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        return new ModelsPemotonganPajak([
            'npwp' => $row['npwp'],
            'nama_vendor' => $row['nama_vendor'],
            'no_faktur' => $row['no_faktur'],
            'tanggal_faktur' => $row['tanggal_faktur'],
            'masa' => $row['masa'],
            'tahun' => $row['tahun'],
            'dpp' => $row['dpp'],
            'ppn' => $row['ppn'],
            'pph' => $row['pph'],
            'no_bupot' => $row['no_bupot'],
            'tgl_bupot' => $row['tgl_bupot'],
            'area' => $row['area'],
        ]);
    }
}
