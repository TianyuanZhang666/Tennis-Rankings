<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTennisRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tennis_rankings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->string('gender',20)->default('');
            $table->string('type',20)->default('');
            $table->integer('ranking')->default(0);
            $table->string('player',50)->default('');
            $table->string('country',20)->default('');
            $table->integer('age')->default(0);
            $table->string('points',20)->default('');
            $table->integer('tournaments')->default(0);
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
        Schema::dropIfExists('tennis_rankings');
    }
}
