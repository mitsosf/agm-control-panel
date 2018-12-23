<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ixudra\Curl\Facades\Curl;

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
 * @property string spot_status
 * @property int role_id
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
        'name', 'surname', 'email', 'role_id', 'section', 'esncard', 'document', 'birthday', 'gender', 'phone', 'esn_country', 'photo', 'tshirt', 'facebook', 'allergies', 'comments', 'workshops', 'fee', 'meal', 'rooming', 'spot_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function esnCardStatus($card)
    {

        $response = Curl::to('https://esncard.org/services/1.0/card.json')
            ->withData(array('code' => $card))
            ->get();


        if (strpos($response, 'active')) {
            return 'active';
        } elseif (strpos($response, 'expired')) {
            return 'expired';
        } elseif (strpos($response, 'available')) {
            return 'available';
        } else {
            return 'invalid';
        }
    }

    public function refreshErsStatus()
    {

        if ($this->spot_status === 'paid') {
            $status = 'paid';
        } else {


            $status = 'pending'; //Default status

            $json = Curl::to(env('ERS_PAYMENTS_API_URL'))
                ->withData(array('event' => env('ERS_PAYMENTS_API_EVENT_ID')))
                ->get();

            if (!isset($json)){
                $this->spot_status = $status;
                $this->update();

                return $status;
            }

            $ers_users = json_decode($json, TRUE);

            foreach ($ers_users as $ers_user) {
                if ($ers_user['esn_accounts_username'] == $this->username) {
                    $status = 'approved';
                }
            }

            $this->spot_status = $status;
            $this->update();
        }
        return $status;

    }
}
