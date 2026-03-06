<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisTamu;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamus';
    protected $primaryKey = 'id_tamu';

    protected $fillable = [
        'nama_tamu',
        'nomor_hp',
        'id_jenis_tamu',
    ];

    // Relasi
   // public function jenisTamu()
   // {
   //     return $this->belongsTo(JenisTamu::class, 'id_jenis_tamu', 'id_jenis_tamu');
   // }

    public function jenisTamu()
{
    return $this->belongsTo(\App\Models\JenisTamu::class, 'id_jenis_tamu', 'id_jenis_tamu');
}


    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'id_tamu', 'id_tamu');
    }
}

