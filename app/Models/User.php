<?php

namespace App\Models;


use App\Enum\Role; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

  
    public function store()
    {
        return $this->hasMany(Store::class);
    }

    public function participant()
    {
        return $this->hasOne(Participant::class);
    }
   

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class, 
        ];
    }

 
    
    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isStore(): bool
    {
        return $this->role === Role::Store;
    }

    public function isParticipant(): bool
    {
        return $this->role === Role::Participant;
    }
}