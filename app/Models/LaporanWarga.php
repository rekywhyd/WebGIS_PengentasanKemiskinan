<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanWarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_calon',
        'jumlah_tanggungan',
        'alamat',
        'deskripsi',
        'foto',
        'status_laporan',
        'id_tempat_ibadah',
    ];

    public function tempat_ibadah()
    {
        return $this->belongsTo(TempatIbadah::class, 'id_tempat_ibadah');
    }
}
