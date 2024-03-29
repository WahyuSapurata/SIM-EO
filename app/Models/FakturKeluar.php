<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class FakturKeluar extends Model
{
    use HasFactory;

    protected $table = 'faktur_keluars';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_persetujuan',
        'npwp',
        'client',
        'no_faktur',
        'tanggal_faktur',
        'masa',
        'tahun',
        'status_faktur',
        'dpp',
        'ppn',
        'event',
        'area',
        'pph',
        'total_tagihan',
        'realisasi_dana_masuk',
        'deskripsi',
        'selisih',
        'no_bupot',
        'tgl_bupot',
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
