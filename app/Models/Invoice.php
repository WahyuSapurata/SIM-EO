<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_vendor',
        'no_invoice',
        'tanggal_invoice',
        'deskripsi',
        'penanggung_jawab',
        'jabatan',
        'uuid_bank',
        'total',
        'uuid_pajak',
        'file',
        'tagihan',
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
