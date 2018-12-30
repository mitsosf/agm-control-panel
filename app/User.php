<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;
use Ixudra\Curl\Facades\Curl;

/**
 * @property mixed id
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
 * @property mixed fee
 * @property mixed transactions
 * @property mixed rooming_comments
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

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
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

            if (empty($json)){
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

    public function generateProof(){

        $user = $this;

        $transactions = $user->transactions()->where('type', 'fee')->get();

        $invoice = null;
        if ($transactions->count() > 0) {
            $invoice = $transactions->first();
        }else{
            return "Invoice is being processed, please check again later";
        }

        $invID = 0;//$invoice->id;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('mails.paymentConfirmation',compact('user', 'invID')));


        //Save invoice locally
        $path = 'invoices/' . $invID . $user->name . $user->surname . $user->esn_country .'Fee.pdf';
        return view('participants.test', compact('invoice'));//$pdf->stream();
    }
}
