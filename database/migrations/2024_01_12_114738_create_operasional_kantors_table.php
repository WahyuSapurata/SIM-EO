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
        Schema::create('operasional_kantors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('deskripsi');
            $table->string('spsifikasi');
            $table->string('harga_satuan');
            $table->string('qty');
            $table->string('qty_satuan');
            $table->string('freq');
            $table->string('freq_satuan');
            $table->string('kategori');
            $table->string('sisa_tagihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operasional_kantors');
    }
};
