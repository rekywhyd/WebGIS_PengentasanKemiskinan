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
        Schema::create('laporan_wargas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_calon');
            $table->integer('jumlah_tanggungan');
            $table->string('foto')->nullable();
            $table->enum('status_laporan', ['menunggu', 'diproses'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_wargas');
    }
};
