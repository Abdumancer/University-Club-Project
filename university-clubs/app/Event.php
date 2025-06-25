<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['club_id', 'title', 'description', 'event_date', 'location', 'created_by'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }
}
