<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class DataVendor extends Model
{
    use HasFactory;

    protected $table = 'data_vendors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'nama_owner',
        'nama_perusahaan',
        'alamat_perusahaan',
        'email',
        'no_telp',
        'nama_bank',
        'nama_pemegan_rek',
        'no_rek',
        'nama_npwp',
        'npwp'
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
