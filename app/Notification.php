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
    protected $appends = ['friend_request', 'notification_come_from'];

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
        return $this->hasOne(User::class, 'id', 'action_id')->select('id', 'name', 'email', 'image');
    }

    public function getFriendRequestAttribute() {
//        dd($this->data->target_id);
        try {
            $model = UserFriend::where('friend_id', \Auth::id())->Where('user_id', $this->data->target_id)->where('status', 'pending')->get();
            if ($model->isEmpty() !== true):
                return false;
            else:
                return true;
            endif;
        } catch (\Exception $ex) {
            return 0;
        }
    }

    public function getnotificationComeFromAttribute() {
        try {
            $model = User::select('id', 'username', 'email', 'image')->where('id', $this->data->target_id)->first();
            return $model;
        } catch (\Exception $ex) {
            return null;
        }
        
    }

}
