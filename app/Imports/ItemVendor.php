<?php

namespace App\Imports;

use App\Models\ItemVendor as ModelsItemVendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemVendor implements ToModel, WithHeadingRow
{
    protected $uuid_vendor;

    public function __construct($uuid_vendor)
    {
        $this->uuid_vendor = $uuid_vendor;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        return new ModelsItemVendor([
            'uuid_vendor' => $this->uuid_vendor,
            'kegiatan' => $row['kegiatan'],
            'qty' => $row['qty'],
            'satuan_kegiatan' => $row['satuan_kegiatan'],
            'freq' => $row['freq'],
            'satuan' => $row['satuan'],
            'harga_satuan' => $row['harga_satuan'],
            'ket' => $row['ket'],
        ]);
    }
}
