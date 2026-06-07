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
        Schema::create('log_distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penerima')->constrained('penerima_bantuan')->onDelete('cascade');
            $table->dateTime('tanggal_diterima');
            $table->string('status')->default('Menunggu');
            $table->text('foto_penyerahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_distribusi');
    }
};
