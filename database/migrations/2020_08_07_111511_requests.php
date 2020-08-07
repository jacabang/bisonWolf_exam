<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Requests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('origin');
            $table->string('destination');
            $table->string('aircraft');
            $table->string('passenger_count');
            $table->date('flight_date');
            $table->time('flight_time')->nullable();
            $table->tinyInteger('has_accommodations');
            $table->tinyInteger('has_exclusions_of_time');
            $table->tinyInteger('has_special_requests');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
