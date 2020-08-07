<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExclusionsOfTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusions_of_time', function (Blueprint $table) {
            $table->increments('id');
            $table->time('from_time');
            $table->time('to_time');
            $table->integer('requests_id')->unsigned()->index();
            $table->foreign('requests_id')->references('id')->on('requests');
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
        Schema::dropIfExists('exclusions_of_time');
    }
}
