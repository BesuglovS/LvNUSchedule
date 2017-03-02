<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Auditorium extends Model
{
    protected $table = 'auditoriums';

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function auditorium_events()
    {
        return $this->hasMany(Auditorium::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
