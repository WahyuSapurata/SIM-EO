<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class FakturMasuk extends Model
{
    use HasFactory;

    protected $table = 'faktur_masuks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_persetujuan',
        'npwp',
        'nama_vendor',
        'no_faktur',
        'tanggal_faktur',
        'masa',
        'tahun',
        'dpp',
        'ppn',
        'pph',
        'no_bupot',
        'tgl_bupot',
        'area',
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
