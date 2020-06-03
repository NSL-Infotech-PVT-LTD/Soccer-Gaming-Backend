<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Message extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

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
    protected $fillable = ['sender_id', 'receiver_id', 'attachment', 'message', 'type', 'team_id', 'player_id', 'is_read_customer', 'replied_by_customer'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    protected $appends = array('target_id', 'receiver_details', 'sender_details');
//
//    public function getDescriptionForEvent($eventName) {
//        return _CLASS_ . " model has been {$eventName}";
//    }
//
    public function getTargetIdAttribute() {
        return \Auth::id() == $this->sender_id ? $this->receiver_id : $this->sender_id;
    }

    public function getReceiverDetailsAttribute() {
        return User::where('id', $this->target_id)->select('id', 'first_name', 'image')->first();
    }
    public function getSenderDetailsAttribute() {
        return User::where('id', $this->sender_id)->select('id', 'first_name', 'image')->first();
    }

    public function receiverDetails() {
        return $this->hasOne(User::class, 'id', 'receiver_id')->select('id', 'first_name', 'image');
    }

}
