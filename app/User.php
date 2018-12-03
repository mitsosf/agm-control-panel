<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string username
 * @property mixed email
 * @property mixed photo
 * @property mixed name
 * @property mixed surname
 * @property mixed esn_country
 * @property mixed birthday
 * @property mixed section
 * @property mixed gender
 * @property mixed facebook
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'surname', 'email', 'role_id', 'section', 'esncard', 'document', 'birthday', 'gender', 'phone', 'esn_country', 'photo','tshirt', 'facebook', 'allergies', 'comments', 'workshops', 'fee', 'meal', 'rooming'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function payments(){
        return $this->hasMany('App\Payment');
    }

    public function room(){
        return $this->belongsTo('App\Room');
    }

    public function role(){
        return $this->belongsTo('App\Role');
    }
}
