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
        Schema::create('real_costs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('uuid_client')->nullable();
            $table->uuid('uuid_penjualan')->nullable();
            $table->string('satuan_real_cost')->nullable();
            $table->string('pajak_po')->nullable();
            $table->string('pajak_pph')->nullable();
            $table->string('disc_item')->nullable();

            $table->string('kegiatan');
            $table->integer('qty');
            $table->string('satuan_kegiatan');
            $table->integer('freq');
            $table->string('satuan');
            $table->string('harga_satuan')->nullable();
            $table->string('ket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_costs');
    }
};
