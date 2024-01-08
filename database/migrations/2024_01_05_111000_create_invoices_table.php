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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('client');
            $table->string('no_invoice');
            $table->string('tanggal_invoice');
            $table->string('deskripsi');
            $table->string('penanggung_jawab');
            $table->string('jabatan');
            $table->uuid('uuid_bank');
            $table->string('total');
            $table->string('pajak');
            $table->string('file');
            $table->string('tagihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
