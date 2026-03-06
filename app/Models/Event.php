<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Event extends Model
{
    use HasFactory;

    // =========================
    // Tabel & Primary Key
    // =========================
    protected $table = 'events';
    protected $primaryKey = 'id_event';

    // =========================
    // Mass assignable
    // =========================
    protected $fillable = [
        'nama_event',
        'tanggal',
        'jam',
        'lokasi',
        'id_kategori',
        'access_code', // jangan lupa kalau pakai kode akses
        
    ];

    // =========================
    // RELASI: Event punya banyak User (many-to-many)
    // Pivot table: event_user
    // id_event di pivot → events.id_event
    // user_id di pivot → users.id
    // =========================
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'event_user',
            'id_event',
            'user_id'
        )->withTimestamps();
    }

    // =========================
    // RELASI: Event punya kategori
    // =========================
    public function kategori()
    {
        return $this->belongsTo(\App\Models\KategoriEvent::class, 'id_kategori', 'id_kategori');
    }

    // =========================
    // RELASI: Event punya presensi (1 event banyak presensi)
    // =========================
    public function presensis()
    {
        return $this->hasMany(\App\Models\Presensi::class, 'id_event', 'id_event');
    }
}
