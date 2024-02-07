<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class NonVendor extends Model
{
    use HasFactory;

    protected $table = 'non_vendors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_realCost',
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
