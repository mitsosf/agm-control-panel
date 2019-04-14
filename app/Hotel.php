<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class Hotel extends Model
{
    public function rooms(){
        return $this->hasMany('App\Room');
    }
}
