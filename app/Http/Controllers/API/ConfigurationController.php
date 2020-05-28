<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Configuration as MyModel;

class ConfigurationController extends ApiController {

    public function getPrivacyPolicyColumn(Request $request, $column) {
        try {
            if (!in_array($column, ['terms_and_conditions', 'private_policy', 'about_us']))
                return parent::error('Please use valid column');
            $key = $column . '_customer';
            return parent::success(MyModel::first()->$key, 200, 'data');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getConfigurationColumn(Request $request, $column) {
        $user = User::findOrFail(\Auth::id());
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

            if (!in_array($column, ['terms_and_conditions', 'private_policy', 'about_us']))
                return parent::error('Please use valid column');
//dd($column);
            $key = '';
//            if ($column == 'terms_and_conditions'):
            if ($user->hasRole('Customer') === true)
                $key = '_customer';
            if ($user->hasRole('Service-provider') === true)
                $key = '_service_provider';
            $var = $column . $key;
            return parent::success(MyModel::first()->$var, 200, 'data');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getConfigurationPlayer(Request $request, $column) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            if (!in_array($column, ['terms_and_conditions', 'private_policy', 'about_us']))
                return parent::error('Please use valid column');
//dd($column);
            $key = '_customer';
            $var = $column . $key;
//            dd(MyModel::toSql());
            return parent::success(MyModel::first()->$var, 200, 'data');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getConfigurationService(Request $request, $column) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            if (!in_array($column, ['terms_and_conditions', 'private_policy', 'about_us']))
                return parent::error('Please use valid column');
            $key = '_service_provider';
            $var = $column . $key;
            return parent::success(MyModel::first()->$var, 200, 'data');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

//    public function testingPush(Request $request) {
//        parent::pushNotifications(['title' => 'testing', 'body' => 'testing body', 'data' => ['target_id' => '1', 'target_model' => 'UserJob', 'data_type' => 'proposal']], '214');
//        return parent::success('test');
//    }
    
    public function testingPush(Request $request) {
        $rules = ['id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            parent::pushNotifications(['title' => 'Test', 'body' => 'Test Body', 'data' => ['target_id' => $request->id, 'target_model' => 'Test', 'data_type' => 'Test']], $request->id, FALSE);
            return parent::success(['Message' => 'Notification Sent']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    

}
