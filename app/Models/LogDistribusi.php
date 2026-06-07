<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogDistribusi extends Model
{
    use HasFactory;

    protected $table = 'log_distribusi';

    protected $fillable = [
        'id_penerima',
        'tanggal_diterima',
        'status',
        'foto_penyerahan',
    ];

    protected $casts = [
        'tanggal_diterima' => 'datetime',
    ];

    public function penerimaBantuan()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'id_penerima');
    }
}
