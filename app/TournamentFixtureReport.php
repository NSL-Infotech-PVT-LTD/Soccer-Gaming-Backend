<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class TournamentFixtureReport extends Model {

    use LogsActivity;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tournament_fixture_reports';

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
    protected $fillable = ['tournament_id', 'tournament_created_by_id', 'fixture_id', 'player_id_1', 'player_id_1_team_id', 'player_id_1_score', 'player_id_2', 'player_id_2_team_id', 'player_id_2_score', 'stage', 'created_by', 'updated_by', 'status'];
    protected $appends = ['player_id_one_team_id', 'player_id_two_team_id'];

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

    public function playerId_1() {
        return $this->hasOne(User::class, 'id', 'player_id_1')->select('id', 'username', 'email', 'image');
    }

    public function playerId_2() {
        return $this->hasOne(User::class, 'id', 'player_id_2')->select('id', 'username', 'email', 'image');
    }

    public function getPlayerIdOneTeamIdAttribute() {
        $model = Team::where('id', $this->player_id_1_team_id);
        if ($model->get()->isEmpty() != true)
            return $model->select('id', 'team_name', 'image')->where('id', $this->player_id_1_team_id)->first();
//        return $this->player_id_1_team_id;
        return (['team_name' => $this->player_id_1_team_id]);
    }

    public function getPlayerIdTwoTeamIdAttribute() {
        $model = Team::where('id', $this->player_id_2_team_id);
        if ($model->get()->isEmpty() != true)
            return $model->select('id', 'team_name', 'image')->where('id', $this->player_id_2_team_id)->first();
//        return $this->player_id_2_team_id;
        return (['team_name' => $this->player_id_2_team_id]);
    }

    public function tournamentDetails() {
        return $this->hasOne(Tournament::class, 'id', 'tournament_id')->select('id', 'name', 'type');
    }

    public function oldScore() {
        return $this->hasOne(TournamentFixture::class, 'id', 'fixture_id')->select('id', 'player_id_1', 'player_id_1_team_id', 'player_id_1_score', 'player_id_2', 'player_id_2_team_id', 'player_id_2_score','created_at', 'updated_at')->with(['playerId_1', 'playerId_2']);
    }

    public function reportedBy() {
        return $this->hasOne(User::class, 'id', 'created_by')->select('id', 'username', 'first_name', 'last_name', 'email');
    }

}
