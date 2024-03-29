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
        Schema::create('faktur_keluars', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('uuid_persetujuan');
            $table->string('npwp')->nullable();
            $table->string('client')->nullable();
            $table->string('no_faktur')->nullable();
            $table->string('tanggal_faktur')->nullable();
            $table->string('masa')->nullable();
            $table->string('tahun')->nullable();
            $table->string('status_faktur')->nullable();
            $table->string('dpp')->nullable();
            $table->string('ppn')->nullable();
            $table->string('event')->nullable();
            $table->string('area')->nullable();
            $table->string('pph')->nullable();
            $table->string('total_tagihan')->nullable();
            $table->string('realisasi_dana_masuk')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('selisih')->nullable();
            $table->string('no_bupot')->nullable();
            $table->string('tgl_bupot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_keluars');
    }
};
