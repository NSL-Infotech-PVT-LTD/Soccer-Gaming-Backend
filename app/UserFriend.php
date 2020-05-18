<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class UserFriend extends Model {

    use HasApiTokens,
        Notifiable,
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'friend_id', 'status'];
    protected $appends = ['user_details'];

    public function getUserDetailsAttribute() {
        $id = ($this->user_id == \Auth::id()) ? $this->friend_id : $this->user_id;
        return User::where('id', $id)->select('id', 'username', 'image')->first();
//        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'username', 'image');
    }

//    public function UserDetails() {
//        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'username', 'image');
//    }
}
