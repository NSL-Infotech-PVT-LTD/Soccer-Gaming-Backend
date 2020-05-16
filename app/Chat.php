<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Chat extends Model
{
use LogsActivity;


/**
* The database table used by the model.
*
* @var string
*/
protected $table = 'chats';

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
protected $fillable = ['user_id', 'sender_id'];



/**
* Change activity log event description
*
* @param string $eventName
*
* @return string
*/
public function getDescriptionForEvent($eventName)
{
return _CLASS_ . " model has been {$eventName}";
}
}