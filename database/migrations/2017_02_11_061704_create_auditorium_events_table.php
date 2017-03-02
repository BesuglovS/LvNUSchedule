<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditoriumEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditorium_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->integer('calendar_id');
            $table->integer('ring_id');
            $table->integer('auditorium_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auditorium_events');
    }
}
