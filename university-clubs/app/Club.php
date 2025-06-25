<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = ['name', 'description', 'created_by', 'approved'];

    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    
    public function members()
    {
        return $this->belongsToMany(\App\User::class, 'memberships', 'club_id', 'user_id');
    }
}
