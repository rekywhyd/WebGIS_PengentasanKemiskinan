<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tempat_ibadah', function (Blueprint $table) {
            $table->string('kode_lapor')->nullable()->unique()->after('id');
        });

        Schema::table('laporan_wargas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tempat_ibadah')->nullable()->after('id');
            // Foreign key jika menggunakan InnoDB, namun karena mungkin ada data tidak sinkron, biarkan saja index biasa.
            $table->index('id_tempat_ibadah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_wargas', function (Blueprint $table) {
            $table->dropColumn('id_tempat_ibadah');
        });

        Schema::table('tempat_ibadah', function (Blueprint $table) {
            $table->dropColumn('kode_lapor');
        });
    }
};
