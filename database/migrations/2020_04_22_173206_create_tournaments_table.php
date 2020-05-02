<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTournamentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->enum('type', ['league', 'knockout', 'league_and_knockout'])->nullable();
            $table->integer('number_of_players')->nullable();
            $table->integer('number_of_teams_per_player')->nullable();
            $table->string('number_of_plays_against_each_team')->nullable(); //only with league
            $table->enum('number_of_players_that_will_be_in_the_knockout_stage', ['16_player', '8_player', '4_player', '2_player'])->nullable(); //only with knockout
            $table->integer('legs_per_match_in_knockout_stage')->nullable(); //only with knockout
            $table->integer('number_of_legs_in_final')->nullable(); //only with knockout
            \App\Helpers\DbExtender::defaultParams($table, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('tournaments');
    }

}
