<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class VoteRound extends Model
{
    public function voteDelegations(){
        return $this->hasMany('App\VoteDelegation');
    }
}
