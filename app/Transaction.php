<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed user_id
 * @property mixed type
 * @property string amount
 * @property string comments
 * @property string approved
 * @property string proof
 */

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id', 'type', 'amount', 'comments', 'approved', 'proof'
    ];

    public function invoice(){
        return $this->hasOne('App\Invoice');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
