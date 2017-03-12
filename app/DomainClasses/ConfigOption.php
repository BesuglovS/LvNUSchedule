<?php

namespace App\DomainClasses;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfigOption extends Model
{
    public static function SemesterStarts()
    {
        return DB::table('config_options')
            ->where('key', '=', 'Semester Starts')
            ->select('value')
            ->get()
            ->map(function($item) { return $item->value;})[0];
    }
}
