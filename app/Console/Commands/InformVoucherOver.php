<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class InformVoucherOver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inform:owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inform about the voucher un available';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $promo_count =   DB::table('tbl_promo_voucher')
            ->select('*')
            ->where('status', '=', 0)
            ->count();
        if($promo_count == 0) {

            $sms = "Voucher is unavailable please add the voucher urgently at VOUCHER SYSTEM ";
            //Your authentication key
            $authKey = "134556AZbJqzDsxSk585abcb1";

            //Multiple mobiles numbers separated by comma
            $mobileNumber = '9725053310';

            //Sender ID,While using route4 sender id should be 6 characters long.
            $senderId = "Vouchr";

            //Your message to send, Add URL encoding here.
            $message = urlencode($sms);

            //Define route
            $route = "4";
            //Prepare you post parameters
            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => $senderId,
                'route' => $route
            );

            //API URL
            $url="http://api.msg91.com/api/sendhttp.php";

            // init the resource
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
            ));
            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //get response
            curl_exec($ch);

            //Print error if any
            if(curl_errno($ch))
            {
                echo 'error:' . curl_error($ch);
            }

            curl_close($ch);
        }
    }
}
