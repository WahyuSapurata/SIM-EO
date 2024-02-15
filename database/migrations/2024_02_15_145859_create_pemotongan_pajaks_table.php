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
        Schema::create('pemotongan_pajaks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('npwp')->nullable();
            $table->string('nama_vendor')->nullable();
            $table->string('no_faktur')->nullable();
            $table->string('tanggal_faktur')->nullable();
            $table->string('masa')->nullable();
            $table->string('tahun')->nullable();
            $table->string('dpp')->nullable();
            $table->string('ppn')->nullable();
            $table->string('pph')->nullable();
            $table->string('no_bupot')->nullable();
            $table->string('tgl_bupot')->nullable();
            $table->string('area')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemotongan_pajaks');
    }
};
