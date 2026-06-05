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
        'foto_kondisi_rumah',
        'lat',
        'lng',
        'alamat'
    ];

    public function tempat_ibadah()
    {
        return $this->belongsTo(TempatIbadah::class, 'id_tempat_ibadah');
    }
}
