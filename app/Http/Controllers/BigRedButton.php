<?php

namespace App\Http\Controllers;

use App\DomainClasses\Discipline_Teacher;
use App\DomainClasses\Faculty_Student_Group;
use App\DomainClasses\Student_Student_Group;

class BigRedButton extends Controller
{
    public function index() {
        return Student_Student_Group::all();
    }
}
