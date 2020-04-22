<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\Notification as MyModel;
use \App\Role;
use Illuminate\Support\Facades\Mail;
use Hash;
use App;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;

class NotificationController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        // dd($category_id);
        try {
//            $user = \App\User::find(Auth::user()->id);
            $model = new MyModel();
            $perPage = isset($request->limit) ? $request->limit : 20;

            if (isset($request->search))
                $model = $model->Where('title', 'LIKE', "%$request->search%")
                        ->orWhere('body', 'LIKE', "%$request->search%")
                        ->orWhere('data', 'LIKE', "%$request->search%");
            

            $model = $model->where('action_id', \Auth::id())->select('id', 'title', 'body', 'data', 'created_by', 'created_at','action_id');
            $model = $model->with('userDetail')->orderBy('created_at', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
