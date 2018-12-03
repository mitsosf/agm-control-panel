<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function hotel(){
        return $this->belongsTo('App\Hotel');
    }

    public function users(){
        return $this->hasMany('App\User');
    }
}
