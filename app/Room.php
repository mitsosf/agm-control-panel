<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed hotel_id
 * @property mixed beds
 * @property mixed code
 * @property mixed final
 */
class Room extends Model
{
    public function hotel(){
        return $this->belongsTo('App\Hotel');
    }

    public function users(){
        return $this->hasMany('App\User');
    }
}
