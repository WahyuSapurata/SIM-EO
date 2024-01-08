<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class FeeManajement extends Model
{
    use HasFactory;

    protected $table = 'fee_manajements';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_client',
        'total_fee',
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
