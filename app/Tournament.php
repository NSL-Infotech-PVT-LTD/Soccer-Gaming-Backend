<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model {

    use LogsActivity;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tournaments';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type', 'number_of_players', 'number_of_teams_per_player', 'number_of_plays_against_each_team', 'number_of_players_that_will_be_in_the_knockout_stage', 'legs_per_match_in_knockout_stage', 'number_of_legs_in_final', 'created_by', 'updated_by'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

    public function players() {
        return $this->hasMany('\App\TournamentPlayerTeam', 'tournament_id', 'id')->select('tournament_id', 'player_id')->groupBY('player_id')->with(['player']);
    }

    public function fixtures() {
        $data = $this->hasMany('\App\TournamentFixture', 'tournament_id', 'id')->select('tournament_id', 'player_id_1', 'player_id_1_team_id', 'player_id_1_score', 'player_id_2', 'player_id_2_team_id', 'player_id_2_score')->with(['playerId_1', 'playerId_2']);
        return $data;
    }

}
