<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
     protected $table = 'configurations';

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
    protected $fillable = ['about_us_customer', 'about_us_service_provider', 'terms_and_conditions_customer', 'terms_and_conditions_service_provider','private_policy_customer','private_policy_service_provider'];



}

