<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Tamu;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';
    protected $primaryKey = 'id_presensi';

    protected $fillable = [
        'id_event',
        'id_tamu',
        'kode_unik',
        'qr_code',
        'status_hadir',
        'waktu_hadir',
    ];

    // Cast tipe data biar rapi
    protected $casts = [
        'status_hadir' => 'boolean',
        'waktu_hadir'  => 'datetime',
    ];

    public $timestamps = true;

    // ========================
    // RELASI
    // ========================

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function tamu()
{
    return $this->belongsTo(\App\Models\Tamu::class, 'id_tamu', 'id_tamu');
}
}
