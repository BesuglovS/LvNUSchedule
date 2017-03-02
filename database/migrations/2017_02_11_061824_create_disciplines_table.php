<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disciplines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('attestation');
            $table->integer('auditorium_hours');
            $table->integer('auditorium_hours_per_week');
            $table->integer('lecture_hours');
            $table->integer('practical_hours');
            $table->boolean('course_project');
            $table->boolean('course_task');
            $table->boolean('control_task');
            $table->boolean('referat');
            $table->boolean('essay');

            $table->integer('student_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disciplines');
    }
}
