<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonLogEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_log_events', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('date_time');
            $table->string('public_comment');
            $table->string('hidden_comment');

            $table->integer('old_lesson_id');
            $table->integer('new_lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_log_events');
    }
}
