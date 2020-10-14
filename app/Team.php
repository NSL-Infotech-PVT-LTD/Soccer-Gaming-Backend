<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';

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
    protected $fillable = ['team_name', 'league_name', 'image'];

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

    public function getTeamNameAttribute($value) {
        try {
            return ucfirst($value);
        } catch (\Exception $ex) {
            return $value;
        }
    }

    public function getLeagueNameAttribute($value) {
        try {
            return ucfirst($value);
        } catch (\Exception $ex) {
            return $value;
        }
    }

    public function getImageAttribute($value) {
        if (strpos($value, 'https://fut.best') !== false) {
            $return = $value;
        }else{
           $return =  env('APP_URL').'uploads/team_image/'.$value; 
        }
        return $return;
        dd($return);
    }
}
