<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class PersetujuanInvoice extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_invoice',
        'status',
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
