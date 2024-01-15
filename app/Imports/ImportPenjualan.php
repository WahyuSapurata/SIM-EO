<?php

namespace App\Imports;

use App\Models\Penjualan;
use App\Models\RealCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPenjualan implements ToModel, WithHeadingRow
{
    protected $uuid_client;

    public function __construct($uuid_client)
    {
        $this->uuid_client = $uuid_client;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        // Membuat dan menyimpan model Penjualan
        $penjualan = new Penjualan([
            'uuid_client' => $this->uuid_client,
            'uuid_user' => auth()->user()->uuid,
            'kegiatan' => $row['kegiatan'],
            'qty' => $row['qty'],
            'satuan_kegiatan' => $row['satuan_kegiatan'],
            'freq' => $row['freq'],
            'satuan' => $row['satuan'],
            'harga_satuan' => $row['harga_satuan'],
            'ket' => $row['ket'],
        ]);

        $penjualan->save();

        // Mengatur UUID penjualan pada model RealCost
        $realCost = new RealCost([
            'uuid_client' => $this->uuid_client,
            'uuid_penjualan' => $penjualan->uuid, // Menggunakan UUID yang dihasilkan oleh Penjualan
            'kegiatan' => $row['kegiatan'],
            'qty' => $row['qty'],
            'satuan_kegiatan' => $row['satuan_kegiatan'],
            'freq' => $row['freq'],
            'satuan' => $row['satuan'],
            'harga_satuan' => $row['harga_satuan'],
            'ket' => $row['ket'],
        ]);

        $realCost->save();

        return compact('penjualan', 'realCost');
    }
}
