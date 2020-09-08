<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentPlayerTeam extends Model {

    use LogsActivity;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */

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
    protected $fillable = ['tournament_id', 'player_id', 'team_id', 'updated_by', 'created_by'];
    protected $appends = ['player_data', 'teams'];

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

    public function player() {
        return $this->hasOne('\App\User', 'id', 'player_id')->select('id', 'username', 'first_name', 'last_name', 'image', 'email');
    }
//    public function getplayerTeamsAttribute($value) {
//        $arr = TournamentPlayerTeam::where('tournament_id', $this->tournament_id)->where('player_id', $this->player_id)->get()->toArray();
//        return $arr;
//    }
    public function getTeamsAttribute($value) {
        $arr = TournamentPlayerTeam::where('tournament_id', $this->tournament_id)->where('player_id', $this->player_id)->get()->pluck('team_id')->toArray();


        $return = [];
        $played = 0;
        $won = 0;
        $draw = 0;
        $losses = 0;
        $scored = 0;
        $against = 0;
        $difference = 0;
        $points = 0;
//        $avgpoints = 0;

        foreach ($arr as $team_id):


            foreach (TournamentFixture::where('tournament_id', $this->tournament_id)->where('player_id_1_team_id', $team_id)->where('player_id_1_score', '!=' ,null)->get() as $tournamentTeam):


                $played = $played + 1;
                $scored = $scored + $tournamentTeam->player_id_1_score;
                $against = $against + $tournamentTeam->player_id_2_score;
                $difference = $scored - $against;

                if ($tournamentTeam->player_id_1_score > $tournamentTeam->player_id_2_score)
                    $won = $won + 1;

                elseif ($tournamentTeam->player_id_1_score < $tournamentTeam->player_id_2_score)
                    $losses = $losses + 1;
                else
                    $draw = $draw + 1;
            endforeach;

            foreach (TournamentFixture::where('tournament_id', $this->tournament_id)->where('player_id_2_team_id', $team_id)->where('player_id_2_score', '!=' ,null)->get() as $tournamentTeam):

                $played = $played + 1;
                $scored = $scored + $tournamentTeam->player_id_2_score;
                $against = $against + $tournamentTeam->player_id_1_score;
                $difference = $scored - $against;

                if ($tournamentTeam->player_id_2_score > $tournamentTeam->player_id_1_score)
                    $won = $won + 1;
                elseif ($tournamentTeam->player_id_2_score < $tournamentTeam->player_id_1_score)
                    $losses = $losses + 1;
                else
                    $draw = $draw + 1;
            endforeach;

            $points = $won * 3 + $draw * 1;
            
//            $fixtures = TournamentFixture::where('tournament_id', $this->tournament_id)->whereNotNull('player_id_1_score')->whereNotNull('player_id_2_score')->get();
//            $avgpoints = ($points > 0)?$points / count($fixtures):0; 
            
            $return[] = ['team' =>(Team::where('id',$team_id)->get()->isEmpty() != true)?Team::where('id',$team_id)->select('id', 'team_name', 'image')->first():(['team_name' => $team_id]), 'played' => $played, 'won' => $won, 'losses' => $losses, 'draw' => $draw, 'scored' => $scored, 'against' => $against, 'difference' => $difference, 'points' => $points];
            $played = 0;
            $won = 0;
            $draw = 0;
            $losses = 0;
            $scored = 0;
            $against = 0;
            $difference = 0;
            $points = 0;

        endforeach;

        return $return['0'];
    }

    public function getPlayerDataAttribute($value) {
        $arr = TournamentPlayerTeam::where('tournament_id', $this->tournament_id)->where('player_id', $this->player_id)->get()->pluck('team_id')->toArray();


        $return = [];
        $played = 0;
        $won = 0;
        $draw = 0;
        $losses = 0;
        $scored = 0;
        $against = 0;
        $difference = 0;
        $points = 0;
        $avgpoints = 0;

        foreach ($arr as $team_id):


            foreach (TournamentFixture::where('tournament_id', $this->tournament_id)->where('player_id_1_team_id', $team_id)->where('player_id_1_score', '!=' ,null)->get() as $tournamentTeam):

                $played = $played + 1;
                $scored = $scored + $tournamentTeam->player_id_1_score;
                $against = $against + $tournamentTeam->player_id_2_score;
                $difference = $scored - $against;

                if ($tournamentTeam->player_id_1_score > $tournamentTeam->player_id_2_score)
                    $won = $won + 1;

                elseif ($tournamentTeam->player_id_1_score < $tournamentTeam->player_id_2_score)
                    $losses = $losses + 1;
                else
                    $draw = $draw + 1;
            endforeach;

            foreach (TournamentFixture::where('tournament_id', $this->tournament_id)->where('player_id_2_team_id', $team_id)->where('player_id_2_score', '!=' ,null)->get() as $tournamentTeam):

                $played = $played + 1;
                $scored = $scored + $tournamentTeam->player_id_2_score;
                $against = $against + $tournamentTeam->player_id_1_score;
                $difference = $scored - $against;

                if ($tournamentTeam->player_id_2_score > $tournamentTeam->player_id_1_score)
                    $won = $won + 1;
                elseif ($tournamentTeam->player_id_2_score < $tournamentTeam->player_id_1_score)
                    $losses = $losses + 1;
                else
                    $draw = $draw + 1;
            endforeach;

            $points = $won * 3 + $draw * 1;

            $fixtures = TournamentFixture::where('tournament_id', $this->tournament_id)->whereNotNull('player_id_1_score')->whereNotNull('player_id_2_score')->get();
            $avgpoints = ($points > 0)?$points / $played:0; 


        endforeach;
        $return = ['played' => $played, 'won' => $won, 'losses' => $losses, 'draw' => $draw, 'scored' => $scored, 'against' => $against, 'difference' => $difference, 'points' => $points, 'avgpoints' =>$avgpoints];
        return $return;
    }

}
