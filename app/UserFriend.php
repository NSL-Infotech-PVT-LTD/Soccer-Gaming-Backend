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
    protected $appends = ['userDetails'];

    public function getUserDetailsAttribute() {
        return User::where('id', $this->user_id)->select('id', 'username', 'image')->first();
//        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'username', 'image');
    }

//    public function UserDetails() {
//        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'username', 'image');
//    }
}
