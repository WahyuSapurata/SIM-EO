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
        Schema::create('persetujuan_pos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('uuid_user');
            $table->string('uuid_penjualan');
            $table->string('no_po');
            $table->string('jatuh_tempo');
            $table->string('client');
            $table->string('event');
            $table->string('total_po');
            $table->string('file');
            $table->integer('sisa_tagihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_pos');
    }
};
