<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string path
 * @property  integer user_id
 * @property  string section
 * @property  string esn_country
 */
class Invoice extends Model
{
    protected $fillable = [
        'path', 'user_id', 'section', 'esn_country'
    ];

    public function payment(){
        return $this->belongsTo('App\Payment');
    }
}
