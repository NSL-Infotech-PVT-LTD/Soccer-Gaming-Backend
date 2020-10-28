<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\UserVehicle;
use App\Rating;

class User extends Authenticatable {

    use HasApiTokens,
        Notifiable,
//        \Illuminate\Database\Eloquent\SoftDeletes,
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password', 'confirm_password', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id', 'remember_token', 'is_notify'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = ['friend_request_sent', 'friend_request_sent_status', 'unread_message'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    protected $appends = array('role');
    public function getUsernameAttribute($value) {
        try {
            return ucfirst($value);
        } catch (\Exception $ex) {
            return $value;
        }
    }

    public function getFirstNameAttribute($value) {
        try {
            return ucfirst($value);
        } catch (\Exception $ex) {
            return $value;
        }
    }

//    public function getRoleAttribute() {
//        try {
//            $rolesID = \DB::table('role_user')->where('user_id', $this->id)->pluck('role_id');
//            if ($rolesID->isEmpty() !== true):
//                $role = Role::whereIn('id', $rolesID);
//                if ($role->get()->isEmpty() !== true)
//                    return $role->select('name', 'id')->with('permission')->first();
//            endif;
//            return [];
//        } catch (Exception $ex) {
//            return [];
//        }
//    }

    public function getmentionAvailablityAttribute($value) {
        return $value == null ? [] : json_decode($value);
    }

    public function getscheduleAttribute($value) {
        return $value == null ? [] : json_decode($value);
    }

    public static function usersIdByPermissionName($name) {

        $permissions = \App\Permission::where('name', 'like', '%' . $name . '%')->get();
        if ($permissions->isEmpty())
            return [];
        $role = \DB::table('permission_role')->where('permission_id', $permissions->first()->id)->get();
        if ($role->isEmpty())
            return [];
        return \DB::table('role_user')->whereIN('role_id', $role->pluck('role_id'))->pluck('user_id')->toArray();
    }

    public function getRatings() {
        return $this->hasMany(Rating::class, 'provider_id', 'id')->with('userDetails');
    }

    public function getFriendRequestSentAttribute() {
//        dd();
        $model = UserFriend::where('user_id', \Auth::id())->where('friend_id', $this->id)->get();

        if ($model->isEmpty() !== true):
            return true;
        else:
            return false;
        endif;
    }

    public function getFriendRequestSentStatusAttribute() {

        $model = UserFriend::where([['user_id', \Auth::id()], ['friend_id', $this->id]])->orWhere([['user_id', $this->id], ['friend_id', \Auth::id()]])->get();

        if ($this->id == \Auth::id()):
            return 'accepted';
        endif;
        if ($model->isEmpty() !== true):
            if ($model->first()->status == 'accepted'):
                return 'accepted';
            elseif ($model->first()->status == 'rejected'):
                return 'rejected';
            elseif ($model->first()->status == 'pending'):
                if ($model->first()->friend_id == \Auth::id()):
                    return 'received';
                else:
                    return 'pending';
                endif;

            endif;
        else:
            return 'not_sent';
        endif;
    }

    public function getUnreadMessageAttribute() {
        $authId = \Auth::id();
        try {
            $model = Message::where('receiver_id', $authId)->where('is_read_customer', '0')->where('sender_id', $this->id)->get();
            if ($model->isEmpty() !== true)
                return $model->count();
            return 0;
        } catch (Exception $ex) {
            return 0;
        }
    }

}
