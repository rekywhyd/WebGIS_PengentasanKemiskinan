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
        'nama_pengurus',
        'kontak_pengurus',
        'radius_meter',
        'lat',
        'lng',
        'alamat',
        'password',
        'kode_lapor',
    ];

    protected static function booted()
    {
        static::creating(function ($tempatIbadah) {
            if (empty($tempatIbadah->kode_lapor)) {
                $tempatIbadah->kode_lapor = \Illuminate\Support\Str::random(10);
            }
        });
    }

    public function penerima_bantuan()
    {
        return $this->hasMany(PenerimaBantuan::class, 'id_tempat_ibadah');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_tempat_ibadah');
    }
}
