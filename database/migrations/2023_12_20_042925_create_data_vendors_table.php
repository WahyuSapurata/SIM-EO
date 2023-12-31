<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_vendors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nama_owner');
            $table->string('nama_perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('email');
            $table->string('no_telp');
            $table->string('nama_bank');
            $table->string('nama_pemegan_rek');
            $table->string('no_rek');
            $table->string('nama_npwp');
            $table->string('npwp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_vendors');
    }
};
