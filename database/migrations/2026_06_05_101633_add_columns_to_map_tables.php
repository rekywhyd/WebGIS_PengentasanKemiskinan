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
        Schema::table('tempat_ibadah', function (Blueprint $table) {
            $table->enum('jenis_tempat_ibadah', ['Masjid', 'Gereja Katolik', 'Gereja Protestan', 'Vihara', 'Pura', 'Klenteng'])->nullable()->after('nama_tempat');
            $table->string('kontak_person')->nullable()->after('alamat');
        });

        Schema::table('penerima_bantuan', function (Blueprint $table) {
            $table->foreignId('id_tempat_ibadah')->nullable()->after('id')->constrained('tempat_ibadah')->nullOnDelete();
            $table->string('nik_kepala_keluarga')->nullable()->unique()->after('nama_kepala_keluarga');
            $table->string('nomor_kk')->nullable()->after('nik_kepala_keluarga');
            $table->integer('jumlah_tanggungan')->nullable()->after('alamat');
            $table->string('foto_kondisi_rumah')->nullable()->after('jumlah_tanggungan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerima_bantuan', function (Blueprint $table) {
            $table->dropForeign(['id_tempat_ibadah']);
            $table->dropColumn(['id_tempat_ibadah', 'nik_kepala_keluarga', 'nomor_kk', 'jumlah_tanggungan', 'foto_kondisi_rumah']);
        });

        Schema::table('tempat_ibadah', function (Blueprint $table) {
            $table->dropColumn(['jenis_tempat_ibadah', 'kontak_person']);
        });
    }
};
