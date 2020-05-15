<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\User;

class Notification extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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
    protected $fillable = ['title', 'body', 'data', 'target_id', 'is_read'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    
    public function getDataAttribute($value) {
       
        return $value = Null ? [] : json_decode($value);
    }
    
    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

    

    public function userDetail() {
        return $this->hasOne(User::class, 'id', 'action_id')->select('id', 'name', 'email','image');
    }

}
