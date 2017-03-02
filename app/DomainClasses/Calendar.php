<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Calendar extends Model
{
    public static function fromDate($date)
    {
        return DB::table('calendars')
            ->where('date', '=', $date)
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});
    }

    public function auditorium_events()
    {
        return $this->hasMany(AuditoriumEvent::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
