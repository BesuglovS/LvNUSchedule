<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class AuditoriumEvent extends Model
{
    public function calendar()
    {
        $this->belongsTo(Calendar::class);
    }

    public function ring()
    {
        $this->belongsTo(Ring::class);
    }

    public function auditorium()
    {
        $this->belongsTo(Auditorium::class);
    }
}
