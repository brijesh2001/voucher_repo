<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Mail;
use App\Mail\SuccessMail;




class OfflinePayment extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_offline_agent';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name','mobile','state', 'created_by', 'updated_by','client_gstn'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $offlinePayment = OfflinePayment::select('tbl_offline_agent.*');
        return $offlinePayment->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return OfflinePayment::select('tbl_offline_agent.*');
    }

    /**
     * Query to get offlinePayment total count
     *
     * @param $dbObject
     * @return integer $offlinePaymentCount
     */
    public static function getOfflinePaymentCount($dbObject)
    {
        $offlinePaymentCount = $dbObject->count();
        return $offlinePaymentCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetOfflinePaymentData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /**
     * scopeGetFilteredData from App/Models/OfflinePayment
     * get filterred offlinePayments
     *
     * @param  object $query
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function scopeGetFilteredData($query, $request)
    {
        $filter = $request->filter;
        $Datefilter = $request->filterDate;
        $filterSelect = $request->filterSelect;

        /**
         * @param string $filter  text type value
         * @param string $Datefilter  date type value
         * @param string $filterSelect select value
         *
         * @return mixed
         */
        return $query->Where(function ($query) use ($filter, $Datefilter, $filterSelect) {
            if (count($filter) > 0) {
                foreach ($filter as $key => $value) {
                    if ($value != "") {
                        $query->where($key, 'LIKE', '%' . trim($value) . '%');
                    }
                }
            }

            if (count($Datefilter) > 0) {
                foreach ($Datefilter as $dtkey => $dtvalue) {
                    if ($dtvalue != "") {
                        $query->where($dtkey, 'LIKE', '%' . date('Y-m-d', strtotime(trim($dtvalue))) . '%');
                    }
                }
            }

            if (count($filterSelect) > 0) {
                foreach ($filterSelect as $Sekey => $Sevalue) {
                    if ($Sevalue != "") {
                        $query->whereRaw('FIND_IN_SET(' . trim($Sevalue) . ',' . $Sekey . ')');
                    }
                }
            }

        });

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortOfflinePaymentData($query, $request)
    {

        return $query->orderBy(config('constant.offlinePaymentDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $column
     * @param  string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortDefaultDataByRaw($query, $column, $dir)
    {
        return $query->orderBy($column, $dir);
    }

    /**
     * Add & update OfflinePayment addOfflinePayment
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addOfflinePayment(array $models = [])
    {

        // For Storing the Agent Data
        $offlinePayment = new OfflinePayment;
        $offlinePayment->email = $models['email'];
        $offlinePayment->name = $models['name'];
        $offlinePayment->mobile = $models['mobile'];
        $offlinePayment->created_at = date('Y-m-d H:i:s');
        $offlinePayment->created_by = Auth::user()->id;
        $offlinePayment->updated_by = Auth::user()->id;
        $offlinePayment->updated_at = date('Y-m-d H:i:s');

        $offlinePaymentId = $offlinePayment->save();

        if ($offlinePaymentId) {
            return $offlinePayment;
        } else {
            return false;
        }
    }

    /**
     * Add & update OfflinePayment addOfflinePayment
     *
     * @param array $models
     * @return  $offlinePaymentId
     */
    public function storeNewAgentPayment(array $models = [])
    {

       $payment =  OfflinePayment::create([
            'name' => $models['name'],
            'client_gstn' => $models['client_gstn'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->id,
            'email' => $models['email'],
            'mobile' => $models['mobile'],
            'state' => $models['state'],
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i:s'),

        ]);

        //For Storing the Enquiry first
        $enquiry = new Enquiry();
        $enquiry_data = $enquiry->addEnquiry($models);

        $saleData = new SaleData();

        $models['enquiry_id'] = $enquiry_data->id;
        //Get the voucher for sending to the customer

        $promo_voucher = new Promo();
        //getting voucher and adding the entry to the table
        $buying_quantity = intval($models['number_of_voucher']);
        //$addpromo = $this->promo->a$modelsddPromo($request->all());
        $unused_voucher = $promo_voucher->getUnusedVoucher();
        if ($buying_quantity > $unused_voucher) {
            return false;
        }
        $voucher_id = [];
        $voucher_data = $promo_voucher->getVoucherByCount($models['number_of_voucher']);

        if (count($voucher_data) > 0) {
            foreach ($voucher_data as $voucher) {
                $voucher_id[] = $voucher->id;
                $request_promo = [];
                $request_promo['status'] = 2;
                $request_promo['id'] = $voucher->id;
                $promo_voucher->updateStatus($request_promo);
            }
        }else {
            return false;
        }
        $voucher_id = implode(",", $voucher_id);
        $models['voucher_id'] = $voucher_id;
        $models['amount'] = $models['rate'] * $models['number_of_voucher'] ;
        $pending_voucher = new PendingVoucher();
        //For adding the voucher code to the mediator table
        $request_pending_data = [];
        $request_pending_data['voucher_id'] = $voucher_id;
        $request_pending_data['enquiry_id'] = $enquiry_data->id;
        $pending_voucher->addPendingVoucher($request_pending_data);


        $pending_voucher_detail = $pending_voucher->getPendingVoucherByField($enquiry_data->id, 'enquiry_id');
        if (count($pending_voucher_detail) > 0) {

            //Voucher to send in the mail
            $voucher_to_send = [];
            $voucher_id = explode(",", $pending_voucher_detail->voucher_id);
            foreach ($voucher_id as $voucher) {
                $update_voucher_data = [];
                $update_voucher_data['status'] = 1;
                $update_voucher_data['id'] = $voucher;
                $voucher_data = $promo_voucher->getPromoByField($voucher,'id');
                if(!empty($voucher_data)) {
                    $voucher_to_send[] = $voucher_data->voucher_code;
                }
                $promo_voucher->updateStatus($update_voucher_data);
            }

            // For deleteing the entries from the pending_voucher table
            $pending_voucher->deletePendingVoucher($enquiry_data->id,'enquiry_id');
            //Prepare data for email sending to Customer

            $customer_email_data = [];
            $customer_email_data['email'] = $models['email'];
            $customer_email_data['name'] = $models['name'];
            $customer_email_data['mobile'] = $models['mobile'];
            $customer_email_data['amount_paid'] = $models['amount'];
            $customer_email_data['payment_id'] = $models['payment_id'];
            $customer_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
            $customer_email_data['date'] = date('d-m-Y');
            $customer_email_data['type'] = 'customer';
            Mail::send(new SuccessMail($customer_email_data));
            //Prepare data for admin
            sleep(2);
            $admin_email_data = [];
            $admin_email_data['email'] = $models['email'];
            $admin_email_data['name'] = $models['name'];
            $admin_email_data['mobile'] = $models['mobile'];
            $admin_email_data['payment_id'] = $models['payment_id'];
            $admin_email_data['amount_paid'] = $models['amount'];
            $admin_email_data['number_of_voucher'] = $models['number_of_voucher'];
            $admin_email_data['instamojo_fee'] = 'NONE';
            $admin_email_data['date'] = date('d-m-Y');
            $admin_email_data['type'] = 'admin';
            $admin_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
            Mail::send(new SuccessMail($admin_email_data));

            sleep(2);
            $mock_test_mail = [];
            $mock_test_mail['type'] = 'mock_test';
            $mock_test_mail['email'] = $models['email'];
            Mail::send(new SuccessMail($mock_test_mail));

            $final_voucher_sms = implode(",", $voucher_to_send);
            $models['voucher_code'] = $final_voucher_sms;
            $models['instamojo_fee'] = 'NONE';
            $models['payment_code'] = 'NONE';
            $models['amount_paid'] = $models['amount'];
            // For sending the SMS to customer
            $this->sendSms($final_voucher_sms,$models['mobile']);
            $sale_data = $saleData->addSaleData($models);

            if ($sale_data) {
                return $sale_data;
            } else {
                return false;
            }

        }
        return $payment;

        //send the voucher and whole cycle of adding the payment and sending voucher

    }

    /**
     * Add & update OfflinePayment addOfflinePayment
     *
     * @param array $models
     * @return boolean true | false
     */
    public function storeExistingAgentPayment(array $models = [])
    {
        //For fetching the current agent detail
        if(!empty($models['user_id'])) {
            $agent_data = $this->getOfflinePaymentByField($models['user_id'],'id');
        }else {
            return false;
        }

        $models['name'] = $agent_data->name;
        $models['email'] = $agent_data->email;
        $models['state'] = $agent_data->state;
        $models['mobile'] = $agent_data->mobile;
        $models['payment_request_id'] = 'OFFLINE';

        //For Storing the Enquiry first
        $enquiry = new Enquiry();
        $enquiry_data = $enquiry->addEnquiry($models);

        //For Adding it to sales data
        $saleData = new SaleData();

        $models['enquiry_id'] = $enquiry_data->id;
        //Get the voucher for sending to the customer

        $promo_voucher = new Promo();
        //getting voucher and adding the entry to the table
        $buying_quantity = intval($models['number_of_voucher']);
        //$addpromo = $this->promo->a$modelsddPromo($request->all());
        $unused_voucher = $promo_voucher->getUnusedVoucher();
        if ($buying_quantity > $unused_voucher) {
            return false;
        }
        $voucher_id = [];
        $voucher_data = $promo_voucher->getVoucherByCount($models['number_of_voucher']);

        if (count($voucher_data) > 0) {
            foreach ($voucher_data as $voucher) {
                $voucher_id[] = $voucher->id;
                $request_promo = [];
                $request_promo['status'] = 2;
                $request_promo['id'] = $voucher->id;
                $promo_voucher->updateStatus($request_promo);
            }
        }else {
            return false;
        }
        $voucher_id = implode(",", $voucher_id);
        $models['voucher_id'] = $voucher_id;
        $models['amount'] = $models['rate'] * $models['number_of_voucher'] ;
        $pending_voucher = new PendingVoucher();
        //For adding the voucher code to the mediator table
        $request_pending_data = [];
        $request_pending_data['voucher_id'] = $voucher_id;
        $request_pending_data['enquiry_id'] = $enquiry_data->id;
        $pending_voucher->addPendingVoucher($request_pending_data);


        $pending_voucher_detail = $pending_voucher->getPendingVoucherByField($enquiry_data->id, 'enquiry_id');
        if (count($pending_voucher_detail) > 0) {

            //Voucher to send in the mail
            $voucher_to_send = [];
            $voucher_id = explode(",", $pending_voucher_detail->voucher_id);
            foreach ($voucher_id as $voucher) {
                $update_voucher_data = [];
                $update_voucher_data['status'] = 1;
                $update_voucher_data['id'] = $voucher;
                $voucher_data = $promo_voucher->getPromoByField($voucher,'id');
                if(!empty($voucher_data)) {
                    $voucher_to_send[] = $voucher_data->voucher_code;
                }
                $promo_voucher->updateStatus($update_voucher_data);
            }

            // For deleteing the entries from the pending_voucher table
            $pending_voucher->deletePendingVoucher($enquiry_data->id,'enquiry_id');
            //Prepare data for email sending to Customer

            $customer_email_data = [];
            $customer_email_data['email'] = $models['email'];
            $customer_email_data['name'] = $models['name'];
            $customer_email_data['mobile'] = $models['mobile'];
            $customer_email_data['amount_paid'] = $models['amount'];
            $customer_email_data['payment_id'] = $models['payment_id'];
            $customer_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
            $customer_email_data['date'] = date('d-m-Y');
            $customer_email_data['type'] = 'customer';
            Mail::send(new SuccessMail($customer_email_data));
            //Prepare data for admin
            sleep(5);
            $admin_email_data = [];
            $admin_email_data['email'] = $models['email'];
            $admin_email_data['name'] = $models['name'];
            $admin_email_data['mobile'] = $models['mobile'];
            $admin_email_data['payment_id'] = $models['payment_id'];
            $admin_email_data['amount_paid'] = $models['amount'];
            $admin_email_data['number_of_voucher'] = $models['number_of_voucher'];
            $admin_email_data['instamojo_fee'] = 'NONE';
            $admin_email_data['date'] = date('d-m-Y');
            $admin_email_data['type'] = 'admin';
            $admin_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
            Mail::send(new SuccessMail($admin_email_data));

            sleep(3);
            $mock_test_mail = [];
            $mock_test_mail['type'] = 'mock_test';
            $mock_test_mail['email'] = $models['email'];
            Mail::send(new SuccessMail($mock_test_mail));

            $final_voucher_sms = implode(",", $voucher_to_send);
            $models['voucher_code'] = $final_voucher_sms;
            $models['instamojo_fee'] = 'NONE';
            $models['payment_code'] = 'NONE';
            $models['amount_paid'] = $models['amount'];
            // For sending the SMS to customer
            $this->sendSms($final_voucher_sms,$models['mobile']);
            $sale_data = $saleData->addSaleData($models);

            if ($sale_data) {
                return $sale_data;
            } else {
                return false;
            }

        }

    }

    /**
     * Add & update Promo addPromo
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateAgent(array $models = [])
    {
        if (isset($models['id'])) {
            $agent_data = OfflinePayment::find($models['id']);
        } else {
            $agent_data = new OfflinePayment();
            $agent_data->created_at = date('Y-m-d H:i:s');
            $agent_data->created_by = Auth::user()->id;

        }
        $agent_data->name = $models['name'];
        $agent_data->client_gstn = $models['client_gstn'];
        $agent_data->email = $models['email'];
        $agent_data->mobile = $models['mobile'];
        $agent_data->state = $models['state'];



        $agent_data->updated_at = date('Y-m-d H:i:s');
        $agent_data->updated_by = Auth::user()->id;
        $promoId = $agent_data->save();

        if ($promoId) {
            return $agent_data;
        } else {
            return false;
        }
    }

    /**
     * get OfflinePayment By fieldname getOfflinePaymentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getOfflinePaymentByField($id, $field_name)
    {
        return OfflinePayment::where($field_name, $id)->first();
    }


    /**
     * Delete OfflinePayment
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteAgent($id)
    {
        $delete = OfflinePayment::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * Create payment request and redirect to payment gateway.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendSms($voucher_code,$mobile)
    {
        $sms = "Your PTE Exam Promo Code :$voucher_code\nPlease share ptepromocode.com to your friends & help them to save money on PTE Exam Booking.";
        //Your authentication key
        $authKey = "134556AZbJqzDsxSk585abcb1";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = $mobile;

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "PTEPRC";

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
