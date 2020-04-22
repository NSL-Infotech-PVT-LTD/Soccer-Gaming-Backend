<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\User;
use App\Tournament;
use Illuminate\Http\Request;

class TournamentController extends ApiController {

    public function ratings(Request $request) {

        $rules = ['rating' => '', 'feed_back' => '', 'provider_id' => 'required|exists:users,id', 'quality_of_repair' => '', 'overall_experience' => '', 'use_again_or_recommend' => '', 'media' => ''];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $input = $request->all();
        if (isset($request->media))
            $input['media'] = parent::__uploadImage($request->file('media'), public_path('uploads/ratings'), $thumbnail = true);
        $input['user_id'] = Auth::id();

        $Ratings = Rating::create($input);
 
        return parent::success(['message' => 'Created Successfully', 'Rating' => $Ratings]);
    }

    public function getRating(Request $request) {

        $rules = ['provider_id' => 'required|exists:users,id', 'limit' => ''];
      
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
      
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
          
            $model = new User;
            $perPage = isset($request->limit) ? $request->limit : 20;

            $model = $model->where('id',$request->provider_id)->with(['getRatings']);
           

            return parent::success($model->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
