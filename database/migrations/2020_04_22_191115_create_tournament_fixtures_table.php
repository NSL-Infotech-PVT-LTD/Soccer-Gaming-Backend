<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_fixtures', function (Blueprint $table) {
           $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            
            $table->bigInteger('player_id_1')->unsigned()->index();
            $table->foreign('player_id_1')->references('id')->on('users')->onDelete('cascade');            
            $table->integer('player_id_1_score')->nullable();
            $table->string('player_id_1_team_id')->nullable();
            
            $table->bigInteger('player_id_2')->unsigned()->index();
            $table->foreign('player_id_2')->references('id')->on('users')->onDelete('cascade');            
            $table->integer('player_id_2_score')->nullable();
            $table->string('player_id_2_team_id')->nullable();
            
            \App\Helpers\DbExtender::defaultParams($table, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_fixtures');
    }
}
