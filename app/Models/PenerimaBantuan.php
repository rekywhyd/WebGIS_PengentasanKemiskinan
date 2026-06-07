<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaBantuan extends Model
{
    use HasFactory;

    protected $table = 'penerima_bantuan';

    protected $fillable = [
        'id_tempat_ibadah',
        'nik_kepala_keluarga',
        'nomor_kk',
        'nama_kepala_keluarga',
        'jumlah_tanggungan',
        'foto',
        'lat',
        'lng',
        'alamat',
        'jarak_ke_penyalur',
        'dokumen_laporan',
        'status_persetujuan',
        'alasan_penolakan',
        'tanggal_pencairan_terakhir',
    ];

    protected $casts = [
        'tanggal_pencairan_terakhir' => 'datetime',
    ];

    public function tempat_ibadah()
    {
        return $this->belongsTo(TempatIbadah::class, 'id_tempat_ibadah');
    }



    public function logDistribusi()
    {
        return $this->hasMany(LogDistribusi::class, 'id_penerima');
    }
}
