<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatIbadah extends Model
{
    use HasFactory;

    protected $table = 'tempat_ibadah';

    protected $fillable = [
        'nama_tempat',
        'jenis_tempat_ibadah',
        'kontak_person',
        'radius_meter',
        'lat',
        'lng',
        'alamat'
    ];

    public function penerima_bantuan()
    {
        return $this->hasMany(PenerimaBantuan::class, 'id_tempat_ibadah');
    }
}
