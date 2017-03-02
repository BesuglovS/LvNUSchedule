<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discipline_Teacher extends Model
{
    protected $table = 'discipline_teacher';

    public static function IdsFromDisciplineIds($groupDisciplineIds)
    {
        return DB::table('discipline_teacher')
            ->whereIn('discipline_id', $groupDisciplineIds)
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});
    }


    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
