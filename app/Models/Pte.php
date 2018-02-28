<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;




class Pte extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_enquiry';
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name','mobile','number_of_voucher','rate','payment_request_id', 'created_by', 'updated_by'
    ];

    /**
     * Process the payment and dispatch the voucher
     *
     * @return $return
     */
    public function payment($request)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:36703c0ed20e303cc560d62233408859",
                "X-Auth-Token:a736dc788e3bc6b8d5bd885bc45acacc"));
        $payload = Array(
            'purpose' => 'PTE Voucher Payment',
            'amount' => $request['amount'],
            'phone' => $request['mobile'],
            'buyer_name' => $request['name'],
            'redirect_url' => 'https://host.pte.com/rdirect',
            'send_email' => false,
            'webhook' => 'https://host.pte.com/webhook',
            'send_sms' => false,
            'email' => $request['email'],
            'allow_repeated_payments' => false
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response,true);
        return $res;
    }
}
