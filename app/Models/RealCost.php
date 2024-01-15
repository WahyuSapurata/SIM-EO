<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class RealCost extends Model
{
    use HasFactory;

    protected $table = 'real_costs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_client',
        'uuid_penjualan',
        'satuan_real_cost',
        'pajak_po',
        'pajak_pph',
        'disc_item',
        'kegiatan',
        'qty',
        'satuan_kegiatan',
        'freq',
        'satuan',
        'harga_satuan',
        'ket',
    ];

    protected static function boot()
    {
        parent::boot();

        // Event listener untuk membuat UUID sebelum menyimpan
        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
