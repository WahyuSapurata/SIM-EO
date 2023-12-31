<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class DataPajak extends Model
{
    use HasFactory;

    protected $table = 'data_pajaks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'deskripsi_pajak',
        'pajak',
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
