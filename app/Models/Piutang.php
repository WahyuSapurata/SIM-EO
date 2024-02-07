<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Piutang extends Model
{
    use HasFactory;

    protected $table = 'piutangs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_user',
        'uuid_persetujuanInvoice',
        'utang',
        'tagihan',
        'ket',
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
