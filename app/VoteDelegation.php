<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteDelegation extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function voteRound(){
        return $this->belongsTo('App\VoteRound');
    }
}
