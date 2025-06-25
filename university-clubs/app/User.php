<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

  
    protected $hidden = [
        'password', 'remember_token',
    ];

  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function clubs()
    {
        return $this->hasMany(Club::class, 'created_by');
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
