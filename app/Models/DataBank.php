<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class DataBank extends Model
{
    use HasFactory;

    protected $table = 'data_banks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'nama_bank',
        'no_rek',
        'cabang',
        'atas_nama'
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
