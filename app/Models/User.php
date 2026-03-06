<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Event;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =====================================================
    // RELASI: User memiliki banyak Event (many-to-many)
    // Pivot table: event_user
    // user_id → users.id
    // id_event → events.id_event
    // =====================================================
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            Event::class,
            'event_user',
            'user_id',
            'id_event'
        )->withTimestamps();
    }
}