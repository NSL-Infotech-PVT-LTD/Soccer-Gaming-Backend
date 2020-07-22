<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\User;
use \App\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Hash;
use App;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;

class AuthController extends ApiController {

    public $successStatus = 200;

    public function register(Request $request) {

        $rules = ['username' => 'required|string|max:255|unique:users', 'first_name' => 'required', 'last_name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required', 'image' => 'required', 'xbox_id' => '', 'ps4_id' => '', 'youtube_id' => '', 'twitch_id' => ''];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);
            $user = \App\User::create($input);
            //Assign role to created user[1=>10,2=>20,]
            $user->assignRole(\App\Role::where('id', 2)->first()->name);
            // create user token for authorization
            $token = $user->createToken('netscape')->accessToken;

//            testing comment
            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);

            //send mail to user as a feedback    
            $dataM = ['subject' => 'Register Notification', 'name' => $request->username, 'to' => $request->email];

            Mail::send('emails.notify', $dataM, function($message) use ($dataM) {
                $message->from('info@tournie.com');
                $message->to($dataM['to']);
                $message->subject($dataM['subject']);
            });
            //ENDS

            return parent::successCreated(['message' => 'Created Successfully', 'token' => $token, 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

//    public function login(Request $request) {
//        try {
//            $rules = ['email' => 'required', 'password' => 'required'];
//            $rules = array_merge($this->requiredParams, $rules);
//
//            $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
//
//            if ($validateAttributes):
//                return $validateAttributes;
//            endif;
//
//            //parent::addUserDeviceData($user, $request);
//            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])):
//                $user = \App\User::find(Auth::user()->id);
//                $user->save();
//
//                if ($user->hasRole('Customer') === true):
//                    $token = $user->createToken('netscape')->accessToken;
//                else:
//                    return parent::error("User not found");
//                endif;
////                $user = $user->with('roles');
//                // Add user device details for firbase
//                parent::addUserDeviceData($user, $request);
//                return parent::successCreated(['message' => 'Login Successfully', 'token' => $token, 'user' => $user]);
//            else:
//                return parent::error("User credentials doesn't matched");
//            endif;
//        } catch (\Exception $ex) {
//            return parent::error($ex->getMessage());
//        }
//    }

    public function Login(Request $request) {
        try {

            $rules = ['email' => 'required', 'password' => 'required'];
            $rules = array_merge($this->requiredParams, $rules);

            $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);

            if ($validateAttributes):
                return $validateAttributes;
            endif;

            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
//                dd('s');
                $user = \App\User::find(Auth::user()->id);
                $user->save();
                $token = $user->createToken('netscape')->accessToken;
                parent::addUserDeviceData($user, $request);
                if (\App\User::whereId(Auth::user()->id)->where('state', '0')->get()->isEmpty())
                    return parent::error("Your account has not activated yet");
                return parent::successCreated(['message' => 'Login Successfully', 'token' => $token, 'user' => $user]);
            } elseif (Auth::attempt(['username' => request('email'), 'password' => request('password')])) {
//                dd('st');
                $user = \App\User::find(Auth::user()->id);
                $user->save();
                $token = $user->createToken('netscape')->accessToken;
                parent::addUserDeviceData($user, $request);
                if (\App\User::whereId(Auth::user()->id)->where('state', '0')->get()->isEmpty())
                    return parent::error("Your account has not activated yet");
                return parent::successCreated(['message' => 'Login Successfully', 'token' => $token, 'user' => $user]);
            } else {
                return parent::error("User credentials doesn't matched");
            }
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function logout(Request $request) {
        $rules = [];

        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

            $user = \App\User::findOrFail(\Auth::id());
            $user->is_login = '0';
            $user->save();
            $device = \App\UserDevice::where('user_id', \Auth::id())->get();
//            dd($device);
            if ($device->isEmpty() === false)
                \App\UserDevice::destroy($device->first()->id);

            return parent::successCreated('Logout Successfully');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function changePassword(Request $request) {
        $rules = ['old_password' => 'required', 'password' => 'required', 'password_confirmation' => 'required|same:password'];

        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            if (\Hash::check($request->old_password, \Auth::User()->password)):
                $model = \App\User::find(\Auth::id());
                $model->password = \Hash::make($request->password);
                $model->save();
                return parent::success(['message' => 'Password Changed Successfully']);
            else:
                return parent::error('Please use valid old password');
            endif;
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function resetPassword(Request $request, Factory $view) {
        //Validating attributes
        $rules = ['email' => 'required|exists:users,email'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        $view->composer('emails.auth.password', function($view) {
            $view->with([
                'title' => trans('front/password.email-title'),
                'intro' => trans('front/password.email-intro'),
                'link' => trans('front/password.email-link'),
                'expire' => trans('front/password.email-expire'),
                'minutes' => trans('front/password.minutes'),
            ]);
        });
//        return parent::success('Email has been send');
//        dd($request->only('email'));
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject(trans('front/password.reset'));
                });
//        dd($response);
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return parent::successCreated('Password reset link sent please check inbox');
            case Password::INVALID_USER:
                return parent::error(trans($response));
            default :
                return parent::error(trans($response));
                break;
        }
        return parent::error('Something Went');
    }

    public function Update(Request $request) {
        $user = \App\User::findOrFail(\Auth::id());

        $rules = ['first_name' => '', 'last_name' => '', 'image' => '', 'xbox_id' => '', 'ps4_id' => '', 'youtube_id' => '', 'twitch_id' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            $input['sport_id']= json_encode($request->sport_id);
            if (isset($request->image))
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/image'), true);

            $user->fill($input);
            $user->save();

            $user = \App\User::whereId($user->id)->select('id', 'first_name', 'last_name', 'email', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id','is_notify')->first();
            return parent::successCreated(['message' => 'Updated Successfully', 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    public function updateNotifyStatus(Request $request) {
//        dd('s');
        $user = \App\User::findOrFail(\Auth::id());

        $rules = ['is_notify' => 'required|in:0,1'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            $input['sport_id']= json_encode($request->sport_id);
            $input['is_notify'] = $request->is_notify;
            $user->fill($input);
            $user->save();

            $user = \App\User::whereId($user->id)->select('id', 'first_name', 'last_name', 'email', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id','is_notify')->first();
            return parent::successCreated(['message' => 'Notification Status Updated Successfully', 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getProfile(Request $request) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = new \App\User();
            $roleusersSA = \DB::table('role_user')->where('role_id', \App\Role::where('name', 'Customer')->first()->id)->pluck('user_id');
            $model = $model->wherein('users.id', $roleusersSA)
                    ->Select('id', 'username', 'first_name', 'last_name', 'email', 'image', 'xbox_id', 'ps4_id', 'youtube_id', 'twitch_id','is_notify');
            $model = $model->groupBy('users.id');
            $model = $model->where('users.id', \Auth::id());
            if (isset($request->search))
                $model = $model->Where('name', 'LIKE', "%$request->search%")
                        ->orWhere('email', 'LIKE', "%$request->search%")
                        ->orWhere('first_name', 'LIKE', "%$request->search%");

            $perPage = isset($request->limit) ? $request->limit : 20;
            return parent::success($model->paginate($perPage)->first());
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

}
