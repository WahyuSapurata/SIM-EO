<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ItemVendor extends Model
{
    use HasFactory;

    protected $table = 'item_vendors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_vendor',
        'kegiatan',
        'qty',
        'satuan_kegiatan',
        'freq',
        'satuan',
        'harga_satuan',
        'ket'
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
