<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class PersetujuanPo extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_pos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_penjualan',
        'uuid_user',
        'no_po',
        'jatuh_tempo',
        'client',
        'event',
        'total_po',
        'file',
        'sisa_tagihan',
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
