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
            // Rename kontak_person to kontak_pengurus
            $table->renameColumn('kontak_person', 'kontak_pengurus');
        });

        Schema::table('tempat_ibadah', function (Blueprint $table) {
            // Add new columns
            $table->string('nama_pengurus')->nullable()->after('kontak_pengurus');
        });

        Schema::table('penerima_bantuan', function (Blueprint $table) {
            // Rename foto_kondisi_rumah to foto (multi-foto, comma-separated)
            $table->renameColumn('foto_kondisi_rumah', 'foto');
        });

        Schema::table('penerima_bantuan', function (Blueprint $table) {
            // Change foto column to text for multi-file support
            $table->text('foto')->nullable()->change();
            // Add new columns
            $table->double('jarak_ke_penyalur')->nullable()->after('foto');
            $table->string('dokumen_laporan')->nullable()->after('jarak_ke_penyalur');
            $table->dateTime('tanggal_pencairan_terakhir')->nullable()->after('dokumen_laporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerima_bantuan', function (Blueprint $table) {
            $table->dropColumn(['jarak_ke_penyalur', 'dokumen_laporan', 'tanggal_pencairan_terakhir']);
        });

        Schema::table('penerima_bantuan', function (Blueprint $table) {
            $table->renameColumn('foto', 'foto_kondisi_rumah');
        });

        Schema::table('tempat_ibadah', function (Blueprint $table) {
            $table->dropColumn(['nama_pengurus']);
        });

        Schema::table('tempat_ibadah', function (Blueprint $table) {
            $table->renameColumn('kontak_pengurus', 'kontak_person');
        });
    }
};
