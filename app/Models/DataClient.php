<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class DataClient extends Model
{
    use HasFactory;

    protected $table = 'data_clients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'nama_client',
        'event',
        'venue',
        'project_date',
        'nama_pic',
        'no_pic',
        'uuid_user',
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
