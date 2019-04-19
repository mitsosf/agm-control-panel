<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
 * @property string document
 * @property string phone
 * @property mixed allergies
 * @property mixed invoice_address
 * @property  mixed invoice_number
 * @property mixed application_id
 * @property mixed delegate
 * @property mixed checkin
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
        'name', 'surname', 'email', 'role_id', 'section', 'esncard', 'document', 'birthday', 'gender', 'phone', 'esn_country', 'photo', 'tshirt', 'facebook', 'allergies', 'comments', 'workshops', 'fee', 'meal', 'rooming', 'spot_status', 'invoice_address', 'invoice_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    public function voteDelegations(){
        return $this->hasMany('App\VoteDelegation');
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

        if ($this->spot_status !== 'pending' && $this->spot_status !== null) {
            //If it's not pending or null
            return $this->spot_status;
        } else {
            $status = 'pending';
            $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
                ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
                ->withData(array('cas_name' => $this->username))
                ->returnResponseObject()
                ->get();

            if ($response->status !== 200) {
                return 'Error while contacting ERS';
            }

            $applications_json = json_decode($response->content);

            foreach ($applications_json as $application) {
                if ($application->cas_name == $this->username) {
                    if (isset($application->spot_status)) {
                        if ($application->spot_status === 'Not Paid ') {
                            $status = 'approved';
                            break; //We want the foreach loop to stop, if we find one entry that's marked as 'Not paid '
                        }
                    }
                }
            }
        }

        $this->spot_status = $status;
        $this->update();
        return $status;
    }

    public function getLatestInvoiceNumberAndAddress()
    {
        $user = $this;

        $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
            ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
            ->withData(array('cas_name' => $this->username))
            ->returnResponseObject()
            ->get();

        if ($response->status !== 200) {
            return 'Error while contacting ERS';
        }

        $applications_json = json_decode($response->content);

        foreach ($applications_json as $application) {
            if (isset($application->spot_status)) {
                if ($application->spot_status === "Not Paid " || $application->spot_status === "Paid " || $application->spot_status === "Granted ") { //Careful of the extra space after the word "Paid"!
                    $user->invoice_address = str_replace("\r\n", "<br>", $application->invoice_address);
                    $user->invoice_number = $application->invoice_number;
                    $user->update();
                    return true;
                }
            }
        }

        return false;
    }

    public function generateProof()
    {

        $user = $this;

        $transactions = $user->transactions()->where('type', 'fee')->with('invoice')->get();

        $invoice = null;
        if ($transactions->count() > 0) {
            $invoice = $transactions->first()->invoice;
        } else {
            return "Invoice is being processed, please check again later";
        }

        $invID = $invoice->id;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('mails.paymentConfirmation', compact('user', 'invID')));

        return $pdf->stream();
    }


    /**
     * @return array with debt transaction and amount if there is a debt transaction or just the amount
     */
    public function calculateDebt(){
        $amount = 0;

        if (is_null(Transaction::where('user_id',$this->id)->where('type', 'deposit')->first())){
            //If user has not deposited
            $amount+=50;
        }

        $debt = Transaction::where('user_id',$this->id)->where('type','debt')->where('approved','0')->first();
        if (!is_null($debt)){
            //If user owes us money
            $amount+=$debt->amount;

            return array(
                "transaction" => $debt,
                "amount" => $amount
            );
        }

        return array(
            "transaction" => null,
            "amount" => $amount
        );
    }
}
