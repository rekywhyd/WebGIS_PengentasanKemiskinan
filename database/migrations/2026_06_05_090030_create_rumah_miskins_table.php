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
        Schema::create('rumah_miskin', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kepala_keluarga');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rumah_miskin');
    }
};
