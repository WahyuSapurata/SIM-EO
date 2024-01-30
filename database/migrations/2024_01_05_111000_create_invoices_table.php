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
            $table->uuid('uuid_vendor');
            $table->uuid('uuid_user');
            $table->string('no_invoice');
            $table->string('tanggal');
            $table->string('tanggal_invoice');
            $table->string('deskripsi');
            $table->string('penanggung_jawab');
            $table->string('jabatan');
            $table->uuid('uuid_bank');
            $table->string('total');
            $table->uuid('uuid_pajak')->nullable();
            $table->string('file')->nullable();
            $table->string('tagihan')->nullable();
            $table->string('ket')->nullable();
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
