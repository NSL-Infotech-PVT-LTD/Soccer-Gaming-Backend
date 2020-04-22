<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\User;

class Rating extends Model {

    use LogsActivity;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ratings';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $appends = array('before_image');

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['provider_id', 'rating', 'feed_back', 'quality_of_repair', 'overall_experience', 'use_again_or_recommend', 'media', 'user_id'];

    public function getBeforeImageAttribute($value) {
        $images = UserJob::where('user_id', $this->user_id)->value('media_1');
   
        $beforeImage = $images == null ? [] : $images;
        return $images; 
    }

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName) {
        return __CLASS__ . " model has been {$eventName}";
    }

    public function userDetails() {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'name', 'email', 'image');
    }

}
