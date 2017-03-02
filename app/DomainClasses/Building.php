<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    public function auditoriums()
    {
        return $this->hasMany(Auditorium::class);
    }
}
