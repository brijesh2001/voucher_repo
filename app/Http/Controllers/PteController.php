<?php

namespace App\Http\Controllers;

use App\Mail\SuccessMail;
use App\Models\Agent;
use App\Models\Enquiry;
use App\Models\PendingVoucher;
use App\Models\Prize;
use App\Models\Promo;
use App\Models\SaleData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pte;
use Validator;
use DB;
use Mail;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Utility;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;


class PteController extends Controller
{

    protected $pte;
    protected $promo;
    protected $prize;
    protected $enquiry;
    protected $saleData;
    protected $pendingVoucher;
    protected $agent;


    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Pte $pte, Promo $promo, Prize $prize, Enquiry $enquiry,
                                PendingVoucher $pendingVoucher,SaleData $saleData,Agent $agent)
    {
        // $this->middleware(['auth', 'checkRole']);
        $this->pte = $pte;
        $this->promo = $promo;
        $this->prize = $prize;
        $this->enquiry = $enquiry;
        $this->saleData = $saleData;
        $this->pendingVoucher = $pendingVoucher;
        $this->agent = $agent;

    }

    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */
    public function customeValidate($data)
    {
        $rules = array(
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|min:10',
            'number_of_voucher' => 'required',
            'state' => 'required'
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "/";
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }


    /**
     * Create payment request and redirect to payment gateway.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createPaymentRequest(Request $request)
    {
        $request_data = $request->all();
        $validations = $this->customeValidate($request->all());
        if ($validations) {
            return $validations;
        }


        // For adding the enquiry and the contact to the CRM Ends here
        $this->createEnquiry($request_data);

        //Now the process of the voucher buying will start

        $buying_quantity = intval($request_data['number_of_voucher']);
        //$addpromo = $this->promo->addPromo($request->all());
        $unused_voucher = $this->promo->getUnusedVoucher();
        if ($buying_quantity > $unused_voucher) {
            $request->session()->flash('alert-danger', 'Total number of voucher available is ' . $unused_voucher);
            return redirect('/')->withInput();
        }

        if(isset($request_data['user_id']) && !empty($request_data['user_id'])){
            $agent_data = $this->agent->getAgentByField($request_data['user_id'],'id');
            if (count($agent_data) > 0) {
                $current_prize = $agent_data->amount;
                $request_data['rate'] = $agent_data->amount;
                $request_data['amount'] = $buying_quantity * $current_prize;
            } else {
                $request->session()->flash('alert-danger', 'Voucher prize is not available please visit after some time');
                return redirect('/')->withInput();
            }
        }else {
            $current_prize_data = $this->prize->getFirstPrize();
            if (count($current_prize_data) > 0) {
                $current_prize = $current_prize_data->rate;
                $request_data['rate'] = $current_prize_data->rate;
                $request_data['amount'] = $buying_quantity * $current_prize;
            } else {
                $request->session()->flash('alert-danger', 'Voucher prize is not available please visit after some time');
                return redirect('/')->withInput();
            }
        }
        //Get the current rate of voucher

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
            'amount' => $request_data['amount'],
            'phone' => $request_data['mobile'],
            'buyer_name' => $request_data['name'],
            'redirect_url' => 'https://www.ptevouchercode.com/pte/redirect',
            'send_email' => false,
            'webhook' => 'https://www.ptevouchercode.com/pte/webhook',
            'send_sms' => false,
            'email' => $request_data['email'],
            'allow_repeated_payments' => false
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if(isset($result["payment_request"]["id"])) {
            $request_data['payment_request_id'] = $result["payment_request"]["id"];
            //For adding the log to the CRM:
            //$this->paymentLog($request_data);
        }else {
            $request->session()->flash('alert-danger', 'Problem occurred while creating payment id please try after some time');
            return redirect('/')->withInput(); 
        }


        //Insert into enquiry

        $enquiry_data = $this->enquiry->addEnquiry($request_data);
        if ($enquiry_data) {
            $number_of_voucher = $enquiry_data->number_of_voucher;
            $voucher_id = [];
            $voucher_data = $this->promo->getVoucherByCount($number_of_voucher);
            if (count($voucher_data) > 0) {
                foreach ($voucher_data as $voucher) {
                    $voucher_id[] = $voucher->id;
                    $request_promo = [];
                    $request_promo['status'] = 2;
                    $request_promo['id'] = $voucher->id;
                    $this->promo->updateStatus($request_promo);
                }
            }
            $voucher_id = implode(",", $voucher_id);

            //For adding the voucher code to the mediator table
            $request_pending_data = [];
            $request_pending_data['voucher_id'] = $voucher_id;
            $request_pending_data['enquiry_id'] = $enquiry_data->id;
            $this->pendingVoucher->addPendingVoucher($request_pending_data);

        }
        return redirect($result["payment_request"]["longurl"].'?embed=form');

    }

    /**
     * Create payment request and redirect to payment gateway.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkPaymentStatus(Request $request)
    {
        $request_data = $request->all();
        if (isset($request_data['payment_id']) && isset($request_data['payment_request_id'])) {
            $PaymentRequestId = $request_data["payment_request_id"];
            $PaymentId = $request_data["payment_id"];
            if (!empty($PaymentRequestId) && !empty($PaymentId)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.instamojo.com/api/1.1/payments/$PaymentId");
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER,
                    array("X-Api-Key:36703c0ed20e303cc560d62233408859",
                        "X-Auth-Token:a736dc788e3bc6b8d5bd885bc45acacc"));

                $response = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($response, true);

                $success = $result["success"];
                $payment_request_status = $result['payment']['status'];
                $amount_paid = $result['payment']['amount'];
                $instamojo_fee = $result['payment']['fees'];
                $payment_id = $result['payment']['payment_id'];


                //After payment get credited and successful
                if (intval($success) == 1) {
                    if ($payment_request_status == "Credit") {
                        //Fetch the user detail and voucher detail
                        $user_detail = $this->enquiry->getEnquiryByField($PaymentRequestId, 'payment_request_id');
                        if (count($user_detail) > 0) {
                            $enquiry_id = $user_detail->id;
                            $number_of_voucher = $user_detail->number_of_voucher;
                            $email = $user_detail->email;
                            $name = $user_detail->name;
                            $mobile = $user_detail->mobile;
                            $rate = $user_detail->rate;
                            $client_gstn = $user_detail->client_gstn;
                            $actual_amount = intval($number_of_voucher) * intval($rate);
                            if ($amount_paid != $actual_amount) {
                                $request->session()->flash('alert-danger', 'Payment amount is not matching the voucher total amount');
                                return redirect('/')->withInput();
                            }
                            $pending_voucher_detail = $this->pendingVoucher->getPendingVoucherByField($enquiry_id, 'enquiry_id');
                            if (count($pending_voucher_detail) > 0) {

                                //Voucher to send in the mail
                                $voucher_to_send = [];
                                $raw_voucher_id = $pending_voucher_detail->voucher_id;
                                $voucher_id = explode(",", $pending_voucher_detail->voucher_id);
                                foreach ($voucher_id as $voucher) {
                                    $update_voucher_data = [];
                                    $update_voucher_data['status'] = 1;
                                    $update_voucher_data['id'] = $voucher;
                                    $voucher_data = $this->promo->getPromoByField($voucher,'id');
                                    if(!empty($voucher_data)) {
                                        $voucher_to_send[] = $voucher_data->voucher_code;
                                    }
                                    $this->promo->updateStatus($update_voucher_data);
                                }

                                // For deleteing the entries from the pending_voucher table
                                $this->pendingVoucher->deletePendingVoucher($enquiry_id,'enquiry_id');
                                //Prepare data for email sending to Customer

                                $customer_email_data = [];
                                $customer_email_data['email'] = $email;
                                $customer_email_data['name'] = $name;
                                $customer_email_data['mobile'] = $mobile;
                                $customer_email_data['amount_paid'] = $amount_paid;
                                $customer_email_data['payment_id'] = $payment_id;
                                $customer_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
                                $customer_email_data['date'] = date('d-m-Y');
                                $customer_email_data['type'] = 'customer';
                                Mail ::send(new SuccessMail($customer_email_data));
                                //Prepare data for admin
                                sleep(5);
                                $admin_email_data = [];
                                $admin_email_data['email'] = $email;
                                $admin_email_data['name'] = $name;
                                $admin_email_data['mobile'] = $mobile;
                                $admin_email_data['payment_id'] = $payment_id;
                                $admin_email_data['amount_paid'] = $amount_paid;
                                $admin_email_data['number_of_voucher'] = $number_of_voucher;
                                $admin_email_data['instamojo_fee'] = $instamojo_fee;
                                $admin_email_data['date'] = date('d-m-Y');
                                $admin_email_data['type'] = 'admin';
                                $admin_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
                                Mail::send(new SuccessMail($admin_email_data));

                                /*sleep(3);
                                $mock_test_mail = [];
                                $mock_test_mail['type'] = 'mock_test';
                                $mock_test_mail['email'] = $email;
                                Mail::send(new SuccessMail($mock_test_mail));*/

                                $final_voucher_sms = implode(",", $voucher_to_send);

                                // For sending the SMS to customer
                                $this->sendSms($final_voucher_sms,$mobile);
                                $sale_data_entry = [];
                                $sale_data_entry['voucher_id'] = $raw_voucher_id;
                                $sale_data_entry['voucher_code'] = implode(",", $voucher_to_send);
                                $sale_data_entry['instamojo_fee'] = $instamojo_fee;
                                $sale_data_entry['enquiry_id'] = $enquiry_id;
                                $sale_data_entry['payment_code'] = $payment_id;
                                $sale_data_entry['rate'] = $rate;
                                $sale_data_entry['amount_paid'] = $amount_paid;
                                $sale_data_entry['number_of_voucher'] = $number_of_voucher;
                                $sale_data_entry['client_gstn'] = $client_gstn;
                                $sale_data = $this->saleData->addSaleData($sale_data_entry);

                                //Preparing for CRM
                                $crm_data = [];
                                $crm_data['email'] = $email;
                                $crm_data['success_data'] = '<br>Voucher Code: </br>'.$sale_data_entry['voucher_code']. '<br> TransactionId:</br> '.$payment_id
                                    .'<br>Amount Paid: </br> '.$amount_paid.'<br>Client GSTN: </br> '.$client_gstn.'<br>Gateway Fees: </br> '.$instamojo_fee;

                                $this->successLead($crm_data);
                                if($sale_data) {

                                    return redirect('/thankyou');
                                }
                            }
                        } else {
                            $request->session()->flash('alert-danger', 'Payment request id not available please contact admin');
                            return redirect('/')->withInput();
                        }
                    }
                }
            }
        }

        $request->session()->flash('alert-danger', 'Something went wrong please contact admin');
        return redirect('/')->withInput();


    }


    /**
     * Create payment request and redirect to payment gateway.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendSms($voucher_code,$mobile)
    {
        $sms = "Your PTE Exam Voucher Code : $voucher_code\nPlease share ptevouchercode.com to your friends & help them to save money on PTE Exam Booking\nCheers";
        //Your authentication key
        $authKey = "134556AZbJqzDsxSk585abcb1";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = $mobile;

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

    /**
     * @param $request_data
     * @desc for adding the enquiry in CRM
     */

    /*public function createEnquiry($request_data)
    {

        //for getting the rate
        //$rate = 12250;
        $current_prize_data = $this->prize->getFirstPrize();
        if (count($current_prize_data) > 0) {
            $rate = $current_prize_data->rate;
        } else {
            $rate = -1;
        }

        //New CRM Curl Request
        $ch = curl_init();
        $url="https://crm.compassoverseas.com/api/add-crm-lead";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $payload = Array(
            'api_token' => 'dqsRaH6hj3YMzfbf5tzsdSukcHHeJhM1At4kp6PTEs4SxK8KKuNBEkFrK40s',
            'name' => $request_data['name'],
            'number' => $request_data['mobile'],
            'country_iso' => 'IN',
            'product_id' => 2,
            'product' => 'Quantity of voucher - '.$request_data['number_of_voucher'],
            'price' => $rate,
            'lead_category' => 2,
            'email' => $request_data['email'],
            'status_id' => 1,
            'lead_owner' => ''
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        //dd($response);
        curl_close($ch);
    }*/

    /**
     * @desc for adding the success entry in CRM
     */
    public function successLead($sale_data_entry){

        $ch = curl_init();
        $url="https://crm.compassoverseas.com/api/convert";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $payload = Array(
            'api_token' => 'dqsRaH6hj3YMzfbf5tzsdSukcHHeJhM1At4kp6PTEs4SxK8KKuNBEkFrK40s',
            'email' => $sale_data_entry['email'],
            'success_data' => $sale_data_entry['success_data']
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @param $payment_request_id
     *
     * @desc for addin the entry of payment log in CRM
     */

    public function paymentLog($requestData)
    {
        //New Payment Log for CRM
        $ch = curl_init();
        $url="https://crm.compassoverseas.com/api/add-payment-log";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $payload = Array(
            'api_token' => 'dqsRaH6hj3YMzfbf5tzsdSukcHHeJhM1At4kp6PTEs4SxK8KKuNBEkFrK40s',
            'email' => $requestData['email'],
            'transaction_id' => $requestData['payment_request_id']
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
    }


    /**
     * New Payment gateway of razor pay
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * */
    public function createOrder(Request $request)
    {
        $request_data = $request->all();
        $validations = $this->customeValidate($request->all());
        if ($validations) {
            return $validations;
        }

        // For adding the enquiry and the contact to the CRM Starts here

        //$this->createEnquiry($request_data);
        // For adding the enquiry and the contact to the CRM Ends here

        // Checking whether voucher are available as demand
        $buying_quantity = intval($request_data['number_of_voucher']);
        $unused_voucher = $this->promo->getUnusedVoucher();
        if ($buying_quantity > $unused_voucher) {
            $request->session()->flash('alert-danger', 'Total number of voucher available is ' . $unused_voucher);
            return redirect('/')->withInput();
        }
            $current_prize_data = $this->prize->getFirstPrize();
            if (count($current_prize_data) > 0) {
                $current_prize = $current_prize_data->rate;
                $request_data['rate'] = $current_prize_data->rate;
                $request_data['amount'] = $buying_quantity * $current_prize;
            } else {
                $request->session()->flash('alert-danger', 'Voucher prize is not available please visit after some time');
                return redirect('/')->withInput();
            }


        // For adding the enquiry

        $enquiry_data = $this->enquiry->addEnquiry($request_data);
        if ($enquiry_data) {
            $number_of_voucher = $enquiry_data->number_of_voucher;
            $receipt_id = $enquiry_data->id;
            $voucher_id = [];
            $voucher_data = $this->promo->getVoucherByCount($number_of_voucher);
            if (count($voucher_data) > 0) {
                foreach ($voucher_data as $voucher) {
                    $voucher_id[] = $voucher->id;
                    $request_promo = [];
                    $request_promo['status'] = 2;
                    $request_promo['id'] = $voucher->id;
                    $this->promo->updateStatus($request_promo);
                }
            }
            $voucher_id = implode(",", $voucher_id);

            //For adding the voucher code to the mediator table
            $request_pending_data = [];
            $request_pending_data['voucher_id'] = $voucher_id;
            $request_pending_data['enquiry_id'] = $enquiry_data->id;
            $this->pendingVoucher->addPendingVoucher($request_pending_data);

            // For creating the order

            $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));

            $razorpayAmount = $request_data['amount'] * 100;

            $orderData = [
                'receipt'         => $receipt_id,
                'amount'          => $request_data['amount'] * 100, // 2000 rupees in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];

            $razorpayOrder = $api->order->create($orderData);
            $request_data['transaction_id'] = $razorpayOrderId = $razorpayOrder['id'];
            //$this-> paymentLog($request_data);

            //Updating the payment_request_id as enquiry is generated first and then from that we are taking enquiry id to feed
            // in the receipt_id above for creating the order. before it will add just offline payment by default but it will update later

            //update the payment_request_id
            $update_enquiry_id['id'] = $receipt_id;
            $update_enquiry_id['payment_request_id'] = $razorpayOrderId;
            $this->enquiry->updatePaymentId($update_enquiry_id);

            $data = [
                "key"               => config('custom.razor_key'),
                "amount"            => $razorpayAmount,
                "name"              => $enquiry_data->name,
                "description"       => "Voucher Payment From Voucher code",
                "image"             => "https://www.ptevouchercode.com/css/front/img/logo.png",
                "prefill"           => [
                    "name"              => $enquiry_data->name,
                    "email"             => $enquiry_data->email,
                    "contact"           => $enquiry_data->mobile,
                ],
                "notes"             => [
                ],
                "theme"             => [
                    "color"             => "#374593"
                ],
                "order_id"          => $razorpayOrderId,
            ];
            $finalData['data'] = $data;
            $finalData['requestData'] = $request_data;
            $finalData['enquiry_id'] = $receipt_id;
            $finalData['title_text'] = 'Buy PTE Voucher @ ₹10381* Only - Get 15 Scored Mock Test Free';
            $finalData['meta_description'] = 'Want to book PTE Academic Exam online? Buy PTE Voucher online at ₹10381* & Save 1050 and get 15 Scored mock tests FREE. Limited Time Offer!';
            return view('front.confirm',$finalData);

        }
    }

    /**
     * @param Request $request
     * @return $this
     *
     * @desc Cofirm payment receieved or not
     */
    public  function confirmPayment(Request $request)
    {
        $request_data = $request->all();
        if(!empty($request_data)) {
            $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));

            $user_detail = $this->enquiry->getEnquiryByField($request_data['enquiry_id'], 'id');

            if (count($user_detail) > 0) {
                try {

                    $payment = $api->payment->fetch($request_data['razorpay_payment_id']);
                    $original_amount = $user_detail->rate * $user_detail->number_of_voucher * 100;
                    if($payment['amount'] != $original_amount) {
                        $request->session()->flash('alert-danger', 'Amount paid is not equal to original amount, please contact admin');
                        return redirect('/')->withInput();
                    }
                    if($payment['status'] != 'captured') {
                        $request->session()->flash('alert-danger', 'Your payment is not captured by gateway, Please contact admin');
                        return redirect('/')->withInput();
                    }

                    // Now do the final process of sending the voucher code and the email

                    //Initializing all the variable for further usage

                    $amount_paid = $original_amount / 100;
                    $payment_id = $payment['id'];
                    $instamojo_fee = $payment['fee'] / 100;
                    $instamojo_other_tax = $payment['tax'] / 100;

                    $enquiry_id = $user_detail->id;
                    $number_of_voucher = $user_detail->number_of_voucher;
                    $email = $user_detail->email;
                    $name = $user_detail->name;
                    $mobile = $user_detail->mobile;
                    $rate = $user_detail->rate;
                    $client_gstn = $user_detail->client_gstn;

                    $pending_voucher_detail = $this->pendingVoucher->getPendingVoucherByField($enquiry_id, 'enquiry_id');
                    if (count($pending_voucher_detail) > 0) {

                        //Voucher to send in the mail
                        $voucher_to_send = [];
                        $raw_voucher_id = $pending_voucher_detail->voucher_id;
                        $voucher_id = explode(",", $pending_voucher_detail->voucher_id);
                        foreach ($voucher_id as $voucher) {
                            $update_voucher_data = [];
                            $update_voucher_data['status'] = 1;
                            $update_voucher_data['id'] = $voucher;
                            $voucher_data = $this->promo->getPromoByField($voucher,'id');
                            if(!empty($voucher_data)) {
                                $voucher_to_send[] = $voucher_data->voucher_code;
                            }
                            $this->promo->updateStatus($update_voucher_data);
                        }

                        // For deleteing the entries from the pending_voucher table
                        $this->pendingVoucher->deletePendingVoucher($enquiry_id,'enquiry_id');

                        $final_voucher_sms = implode(",", $voucher_to_send);

                        // For sending the SMS to customer
                        $this->sendSms($final_voucher_sms,$mobile);
                        $sale_data_entry = [];
                        $sale_data_entry['voucher_id'] = $raw_voucher_id;
                        $sale_data_entry['voucher_code'] = implode(",", $voucher_to_send);
                        $sale_data_entry['instamojo_fee'] = $instamojo_fee;
                        $sale_data_entry['enquiry_id'] = $enquiry_id;
                        $sale_data_entry['payment_code'] = $payment_id;
                        $sale_data_entry['rate'] = $rate;
                        $sale_data_entry['amount_paid'] = $amount_paid;
                        $sale_data_entry['number_of_voucher'] = $number_of_voucher;
                        $sale_data_entry['client_gstn'] = $client_gstn;
                        $sale_data = $this->saleData->addSaleData($sale_data_entry);

                        //Preparing for CRM
                        $crm_data = [];
                        $crm_data['email'] = $email;
                        $crm_data['success_data'] = 'Voucher Code: '.$sale_data_entry['voucher_code'].
                            'TransactionId: '.$payment_id.'Amount Paid: '.$amount_paid
                            .'Client GSTN: '.$client_gstn. 'Gateway Fees: '.$instamojo_fee;

                        $this->successLead($crm_data);

                        //Prepare data for email sending to Customer

                        $customer_email_data = [];
                        $customer_email_data['email'] = $email;
                        $customer_email_data['name'] = $name;
                        $customer_email_data['mobile'] = $mobile;
                        $customer_email_data['amount_paid'] = $amount_paid;
                        $customer_email_data['payment_id'] = $payment_id;
                        $customer_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
                        $customer_email_data['date'] = date('d-m-Y');
                        $customer_email_data['type'] = 'customer';
                        Mail ::send(new SuccessMail($customer_email_data));
                        //Prepare data for admin
                        sleep(5);
                        $admin_email_data = [];
                        $admin_email_data['email'] = $email;
                        $admin_email_data['name'] = $name;
                        $admin_email_data['mobile'] = $mobile;
                        $admin_email_data['payment_id'] = $payment_id;
                        $admin_email_data['amount_paid'] = $amount_paid;
                        $admin_email_data['number_of_voucher'] = $number_of_voucher;
                        $admin_email_data['instamojo_fee'] = $instamojo_fee;
                        $admin_email_data['instamojo_other_tax'] = $instamojo_other_tax;
                        $admin_email_data['date'] = date('d-m-Y');
                        $admin_email_data['type'] = 'admin';
                        $admin_email_data['voucher_to_send'] = implode(",", $voucher_to_send);
                        Mail::send(new SuccessMail($admin_email_data));

                        /*sleep(3);
                        $mock_test_mail = [];
                        $mock_test_mail['type'] = 'mock_test';
                        $mock_test_mail['email'] = $email;
                        Mail::send(new SuccessMail($mock_test_mail));*/

                        if($sale_data) {

                            return redirect('/thankyou');
                        }
                    }

                } catch (\Exception $e) {
                    $error =   $e->getMessage();
                    $request->session()->flash('alert-danger', $error);
                    return redirect('/')->withInput();
                }

            }

        }else {
            $request->session()->flash('alert-danger', 'Error occurred, please contact Admin');
            return redirect('/')->withInput();
        }
    }

}
