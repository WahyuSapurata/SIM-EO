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
        Schema::create('data_clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nama_client');
            $table->string('event');
            $table->string('venue');
            $table->string('project_date');
            $table->string('nama_pic');
            $table->string('no_pic');
            $table->uuid('uuid_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_clients');
    }
};
