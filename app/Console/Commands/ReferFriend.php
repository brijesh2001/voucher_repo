<?php

namespace App\Console\Commands;

use App\Models\Enquiry;
use App\Models\SaleData;
use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Mail;
use App\Mail\SuccessMail;

class ReferFriend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:referfriend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send referal email';

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
     * For updating the status of unused voucher
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::yesterday()->format('Y-m-d');
        $countYesterday = SaleData::whereDate('created_at', $date )->get();
        if(!empty($countYesterday)) {
            $enquiry = new Enquiry();
            foreach ($countYesterday as $countYesterdayData) {
                $enquiryData = $enquiry->getEnquiryByField($countYesterdayData->id,'id');
                if(!empty($enquiryData)) {
                    $customer_email_data = [];
                    $customer_email_data['type'] = 'refer';
                    $customer_email_data['email'] = $enquiryData->email;
                    Mail ::send(new SuccessMail($customer_email_data));
                    sleep(2);
                }
            }

        }
    }
}
