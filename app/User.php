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
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password', 'confirm_password', 'image', 'field_to_play', 'field_to_play_id', 'video_stream', 'video_stream_id', 'remember_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = ['friend_request_sent','friend_request_sent_status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    protected $appends = array('role');
//    public function getUserImageAttribute($value) {
//        return User::where('id', $this->created_by)->value('image');
//    }
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
        $model = UserFriend::where('user_id', \Auth::id())->where('friend_id',$this->id)->get();
        
        if ($model->isEmpty() !== true):
            return true;
        else:
            return false;
        endif;
    }
    public function getFriendRequestSentStatusAttribute() {
//        dd();
        $model = UserFriend::where('user_id', \Auth::id())->where('friend_id',$this->id)->get();
        
        if ($model->isEmpty() !== true):
            if($model->first()->status == 'accepted'):
                return 'accepted';
            elseif($model->first()->status == 'rejected'):
                return 'rejected';
            elseif($model->first()->status == 'pending'):
                return 'pending';
            endif;
        else:
            return 'not_sent';
        endif;
    }

}
