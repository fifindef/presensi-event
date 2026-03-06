<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTamu extends Model
{
    use HasFactory;

    protected $table = 'jenis_tamus';
    protected $primaryKey = 'id_jenis_tamu';

    protected $fillable = [
        'nama_jenis',
    ];

    // Relasi
    public function tamus()
    {
        return $this->hasMany(Tamu::class, 'id_jenis_tamu', 'id_jenis_tamu');
    }
}
