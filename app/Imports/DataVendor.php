<?php

namespace App\Imports;

use App\Models\DataVendor as ModelsDataVendor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataVendor implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        return new ModelsDataVendor([
            'nama_owner' => $row['nama_owner'],
            'nama_perusahaan' => $row['nama_perusahaan'],
            'alamat_perusahaan' => $row['alamat_perusahaan'],
            'email' => $row['email'],
            'no_telp' => $row['no_telp'],
            'nama_bank' => $row['nama_bank'],
            'nama_pemegan_rek' => $row['nama_pemegan_rek'],
            'no_rek' => $row['no_rek'],
            'nama_npwp' => $row['nama_npwp'],
            'npwp' => $row['npwp'],
        ]);
    }
}
