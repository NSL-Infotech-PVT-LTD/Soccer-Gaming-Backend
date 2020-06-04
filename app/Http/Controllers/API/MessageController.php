<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\User;
use \App\Role;
use Illuminate\Support\Facades\Mail;
use Hash;
use App;
use App\Message as MyModel;

class MessageController extends ApiController {

// private static $__selectedAttributes = ['id','name','date','from_time','to_time','description','image'];

    public function store(Request $request) {
        
        $rules = ['receiver_id' => 'required|exists:users,id', 'message' => 'required', 'player_id' => 'required|exists:users,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        
        try {
            $data = ['sender_id' => "" . \Auth::id() . "", 'receiver_id' => $request->receiver_id, 'message' => $request->message, 'replied_by_customer' => '1'];
            
// dd($data);
            $model = MyModel::create($data);
// dd($model);

            parent::pushNotifications(['title' => 'New Message Received', 'body' => $request->message, 'data' => ['target_id' => \Auth::id(), 'target_model' => 'Message', 'data_type' => 'message']], $request->receiver_id, \Auth::id());


            return parent::success(['message' => 'Sent Successfully', 'model_data' => $model]);
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

    public function getItems(Request $request) {
//        dd('s');
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $authId = \Auth::id();
            $ss = DB::raw("SELECT MAX(id) id FROM messages WHERE " . $authId . " IN(sender_id,receiver_id)"
                            . " GROUP BY LEAST(sender_id,receiver_id), GREATEST(sender_id,receiver_id)");
// dd($ss);
            $model = MyModel::select('messages.*')
                    ->join(\DB::raw("(" . $ss . ") as messagessql"), function($join) {
                $join->on('messagessql.id', '=', 'messages.id');
            }
            );


            if (User::findOrFail(\Auth::id())->hasRole('Service-provider') === true)
// $model = $model->where('messages.replied_by_customer', '1');
                $model = $model->orderBy('id', 'desc');
// $model = 'SELECT a.* FROM messages a '
// . 'JOIN '
// . '( '
// . 'SELECT MAX(id) id FROM messages '
// . 'WHERE 194 IN(sender_id,receiver_id)'
// . ' GROUP BY LEAST(sender_id,receiver_id) ,'
// . ' GREATEST(sender_id,receiver_id)'
// . ') b '
// . 'ON b.id = a.id';
            return parent::success($model->get());
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

    public function getItemsByReceiverId(Request $request) {
        $rules = ['search' => '', 'limit' => '', 'receiver_id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = User::findOrFail(\Auth::id());
//Mark Message Read start
            $authId = \Auth::id();
            $model = MyModel::where(function ($query) use($authId, $request) {
                        $query->where('sender_id', $authId)->where('receiver_id', $request->receiver_id);
                    })->orWhere(function($query)use($authId, $request) {
                $query->where('sender_id', $request->receiver_id)->where('receiver_id', $authId);
            });
// $ids = $model->get()->pluck('id');



            if ($user->hasRole('Customer') === true)
                MyModel::whereIn('id', $model->get()->pluck('id'))->update(['is_read_customer' => '1']);
            
//Mark Message Read end

            $model = new MyModel();
            $perPage = isset($request->limit) ? $request->limit : 50;
            if (isset($request->search))
                $model = $model->Where('message', 'LIKE', "%$request->search%");
            $model = $model->select('messages.id', 'messages.is_read_customer', 'messages.sender_id', 'messages.receiver_id', 'messages.attachment', 'messages.message', 'messages.type', 'messages.created_at');
// $model = $model->where('sender_id', \Auth::id());
            $authId = \Auth::id();
            $model = $model->where(function ($query) use($authId, $request) {
                        $query->where('sender_id', $authId)->where('receiver_id', $request->receiver_id);
                    })->orWhere(function($query)use($authId, $request) {
                $query->where('sender_id', $request->receiver_id)->where('receiver_id', $authId);
            });
//            $model = $model->with(['proposalDetails']);
            $model = $model->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {

            return parent::error($ex->getMessage());
        }
    }

}
