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

        $piutang = new RealCost([
            'uuid_client' => $this->uuid_client,
            'kegiatan' => $row['kegiatan'],
            'qty' => $row['qty'],
            'satuan_kegiatan' => $row['satuan_kegiatan'],
            'freq' => $row['freq'],
            'satuan' => $row['satuan'],
            'harga_satuan' => $row['harga_satuan'],
            'ket' => $row['ket'],
        ]);

        return compact('penjualan', 'piutang');
    }
}
