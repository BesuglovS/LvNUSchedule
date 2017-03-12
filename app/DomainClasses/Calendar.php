<?php

namespace App\DomainClasses;

use Carbon\Carbon;
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

    public static function IdsFromWeek($week)
    {
        $startOfWeek = Carbon::parse(ConfigOption::SemesterStarts())
            ->startOfWeek()->addWeek($week - 1);
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        return DB::table('calendars')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});
    }

    public static function GetWeekNumber($date = null)
    {
        if (is_null($date))
        {
            $date = Carbon::now();
        }
        else {
            $date = Carbon::createFromFormat("Y-m-d", $date);
        }

        $ss = Carbon::parse(ConfigOption::SemesterStarts());
        $diff = $ss->diffInDays($date);
        $week = 1 + (int)($diff / 7);

        return $week;
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
