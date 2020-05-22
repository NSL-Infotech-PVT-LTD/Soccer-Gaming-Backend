<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class TournamentFixture extends Model {

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
    protected $fillable = ['tournament_id', 'player_id_1', 'player_id_1_team_id', 'player_id_1_score', 'player_id_2', 'player_id_2_team_id', 'player_id_2_score', 'created_by', 'updated_by'];

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
    
    public function playerId1Details() {
        return $this->hasOne(User::class, 'id', 'player_id_1')->select('id', 'username', 'email', 'image');
    }
    
    public function playerId2Details() {
        return $this->hasOne(User::class, 'id', 'player_id_2')->select('id', 'username', 'email', 'image');
    }
    
    public function playerId1teamIdDetails() {
        return $this->hasOne(Team::class, 'id', 'player_id_1_team_id')->select('id', 'team_name', 'image');
    }
    
    public function playerId2teamIdDetails() {
        return $this->hasOne(Team::class, 'id', 'player_id_2_team_id')->select('id', 'team_name', 'image');
    }
    

}
