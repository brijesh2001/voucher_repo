<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OfflinePayment;
use Validator;
use DB;
use Excel;
use App\Mail\InvoiceMail;
use Mail;
use Carbon\Carbon;
use PDF;

class OfflinePaymentController extends Controller
{

    protected $offlinePayment;
    public $state;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OfflinePayment $offlinePayment,State $state)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->offlinePayment = $offlinePayment;
        $this->state = $state;

    }

    /**
     * Display a listing of the offlinePayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/OfflinePayment
         *
         * @return mixed
         */
        $data['offlinePaymentData'] = $this->offlinePayment->getCollection();
        $data['offlinePaymentManagementTab'] = "active open";
        $data['offlineAgentPaymentTab'] = "active";
        return view('offlinepayment.existing_agent_list', $data);
    }

    /**
     * Display a listing of the offlinePayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function addNewAgentPayment()
    {

        /**
         * getCollection from App/Models/OfflinePayment
         *
         * @return mixed
         */
        //$data['offlinePaymentData'] = $this->offlinePayment->getCollection();
        $data['offlinePaymentManagementTab'] = "active open";
        $data['state'] = $this->state->getCollection();
        $data['addNewAgentPaymentTab'] = "active";
        return view('offlinepayment.add_new_agent_payment', $data);
    }

    /**
     * Display a listing of the offlinePayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function addExistingAgentPayment()
    {
        $data['agentData'] = $this->offlinePayment->getUniqueOfflineAgent();
        $data['state'] = $this->state->getCollection();
        $data['offlinePaymentManagementTab'] = "active open";
        $data['addExistingAgentPaymentTab'] = "active";
        return view('offlinepayment.add_existing_agent_payment', $data);
    }

    public function datatable(Request $request)
    {
        // default count of offlinePayment $offlinePaymentCount
        $offlinePaymentCount = 0;

        /**
         * getDatatableCollection from App/Models/OfflinePayment
         * get all offlinePayments
         *
         * @return mixed
         */
        $offlinePaymentData = $this->offlinePayment->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/OfflinePayment
         * get filterred offlinePayments
         *
         * @return mixed
         */
        $offlinePaymentData = $offlinePaymentData->GetFilteredData($request);

        /**
         * getOfflinePaymentCount from App/Models/OfflinePayment
         * get count of offlinePayments
         *
         * @return integer
         */
        $offlinePaymentCount = $this->offlinePayment->getOfflinePaymentCount($offlinePaymentData);

        // Sorting offlinePayment data base on requested sort order
        if (isset(config('constant.offlinePaymentDataTableFieldArray')[$request->order['0']['column']])) {
            $offlinePaymentData = $offlinePaymentData->SortOfflinePaymentData($request);
        } else {
            $offlinePaymentData = $offlinePaymentData->SortDefaultDataByRaw('tbl_offline_payment.id', 'desc');
        }

        /**
         * get paginated collection of offlinePayment
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $offlinePaymentData = $offlinePaymentData->GetOfflinePaymentData($request);
        }else {
            $excelraw = $offlinePaymentData->GetFilteredOfflinePaymentData();
            $offlinePaymentData = $offlinePaymentData->GetFilteredOfflinePaymentData($request);
            $file_name =  $this->generateExcel($excelraw);
            if(!empty($file_name)) {
                $url = $file_name['file'];
            }

        }
        $appData = array();
        foreach ($offlinePaymentData as $offlinePaymentData) {
            $row = array();
            $row[] = date("d-m-Y", strtotime($offlinePaymentData->payment_date));
            $row[] = $offlinePaymentData->invoice_no;
            $row[] = $offlinePaymentData->name;
            $row[] = $offlinePaymentData->email;
            $row[] = $offlinePaymentData->mobile;
            $row[] = $offlinePaymentData->gstn;
            $row[] = $offlinePaymentData->hsn;
            $row[] = $offlinePaymentData->voucher_code;
            $row[] = $offlinePaymentData->number_of_voucher;
            $row[] = $offlinePaymentData->transaction_id;
            $row[] = $offlinePaymentData->rate_before_gst;
            $row[] = $offlinePaymentData->sgst;
            $row[] = $offlinePaymentData->cgst;
            $row[] = $offlinePaymentData->igst;
            $row[] = $offlinePaymentData->rate_after_gst;
            $row[] = $offlinePaymentData->payment_type;
            $row[] = $offlinePaymentData->state_name;
            $row[] = view('datatable.action', ['module' => "offline",'type' => $offlinePaymentData->id, 'id' => $offlinePaymentData->id])->render();
            $row[] = view('datatable.pdf', ['module' => "agent",'type' => 'offline', 'id' => $offlinePaymentData->id])->render();
            $row[] = view('datatable.send', ['module' => "agent",'type' => $offlinePaymentData->id, 'id' => $offlinePaymentData->id])->render();
            $appData[] = $row;
        }

        $return_data =  [
            'draw' => $request->draw,
            'recordsTotal' => $offlinePaymentCount,
            'recordsFiltered' => $offlinePaymentCount,
            'data' => $appData,
        ];
        if($request['filterExport']['export_excel'] == 1) {
            $return_data['url'] = $url;
        }
        return $return_data;
    }

    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */
    public function customeValidate($data, $mode)
    {
        if ($mode == 'add-new-agent') {
            $rules = array(
                'name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required',
                'number_of_voucher' => 'required',
                'voucher_code' => 'required',
                'rate_after_gst' => 'required',
                'state' => 'required',
                'payment_date' => 'required',
                'transaction_id' => 'required',

            );
        }
            if ($mode == 'edit-agent') {
                $rules = array(

                    'name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required',
                    'number_of_voucher' => 'required',
                    'voucher_code' => 'required',
                    'rate_after_gst' => 'required',
                    'state' => 'required',
                    'payment_date' => 'required',
                    'transaction_id' => 'required',
                );
            }
        if ($mode == 'add-existing-agent') {
            $rules = array(
                'user_id' => 'required',
                'number_of_voucher' => 'required',
                'voucher_code' => 'required',
                'rate_after_gst' => 'required',
                'payment_date' => 'required',
                'transaction_id' => 'required',
            );
        }

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                $errorRedirectUrl = "offline/add-new-agent";
                if ($mode == "add-new-agent") {
                    $errorRedirectUrl = "offline/add-new-agent/";
                }
                if ($mode == "add-existing-agent") {
                    $errorRedirectUrl = "offline/add-existing-agent-payment/";
                }
                if ($mode == "edit-agent") {
                    $errorRedirectUrl = "offline/edit/" . $data['id'];
                }
                return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
            }
            return false;
        }

    /**
     * Store new agent payment
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
        public function storeNewAgentPayment(request $request)
        {

            $validations = $this->customeValidate($request->all(), 'add-new-agent');
            if ($validations) {
                return $validations;
            }

            // Start Communicate with database
            DB::beginTransaction();
            try {
                $addagent = $this->offlinePayment->storeNewAgentPayment($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('offline/add-new-agent')->withInput();

            }
            if ($addagent) {
                //Event::fire(new SendMail($addprize));
                $request->session()->flash('alert-success', __('app.default_add_success', ["module" => __('app.offline_payment_managment')]));
                return redirect('offline/list');
            } else {
                $request->session()->flash('alert-danger', __('app.default_error', ["module" => __('app.offline_payment_managment'), "action" => __('app.add')]));
                return redirect('offline/add-new-agent')->withInput();
            }
        }

    /**
     * Store new agent payment
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function storeExistingAgentPayment(request $request)
    {

        $validations = $this->customeValidate($request->all(), 'add-existing-agent');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try {
            $requestData = $request->all();
            if(isset($requestData['user_id']) && !empty ($requestData['user_id'])) {
                $sotredAgentData = $this->offlinePayment->getOfflinePaymentByField($requestData['user_id'],'id');
                if(!empty($sotredAgentData)) {
                    $requestData['name'] = $sotredAgentData->name;
                    $requestData['email'] = $sotredAgentData->email;
                    $requestData['mobile'] = $sotredAgentData->mobile;
                    $requestData['gstn'] = $sotredAgentData->gstn;
                    $requestData['state'] = $sotredAgentData->state;
                    $addagent = $this->offlinePayment->storeNewAgentPayment($requestData);
                    DB::commit();
                }else {

                    return false;
                }

            }
         } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('offline/add-existing-agent-payment')->withInput();

        }
        if ($addagent) {
            //Event::fire(new SendMail($addprize));
            $request->session()->flash('alert-success', __('app.default_add_success', ["module" => __('app.offline_payment_managment')]));
            return redirect('offline/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error', ["module" => __('app.offline_payment_managment'), "action" => __('app.add')]));
            return redirect('offline/add-existing-agent-payment')->withInput();
        }
    }


        /**
         * Delete the specified promo in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function delete(request $request)
        {
            $deletePromo = $this->offlinePayment->deleteAgent($request->id);
            if ($deletePromo) {
                $request->session()->flash('alert-success',"Entry deleted successfully");
            } else {
                $request->session()->flash('alert-danger', __('app.default_error', ["module" => __('app.voucher'), "action" => __('app.delete')]));
            }
            echo 1;
        }

        /**
         * Display the specified promo.
         *
         * @param  integer $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {

            /**
             * get details of the specified promo. from App/Models/Promo
             *
             * @param mixed $id
             * @param string (id) fieldname
             * @return mixed
             */
            $data['details'] = $this->offlinePayment->getOfflinePaymentByField($id, 'id');
            $data['agentData'] = $this->offlinePayment->getCollection();
            $data['state'] = $this->state->getCollection();
            $data['offlinePaymentManagementTab'] = "active open";
            $data['addExistingAgentPaymentTab'] = "active";
            return view('offlinepayment.edit_existing_agent_payment', $data);
        }

        /**
         * Update the specified promo in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function update(request $request)
        {
            $validations = $this->customeValidate($request->all(), 'edit-agent');
            if ($validations) {
                return $validations;
            }

            // Start Communicate with database
            DB::beginTransaction();
            try {
                $addpromo = $this->offlinePayment->updateAgent($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('offline/edit/' . $request->get('id'))->withInput();

            }

            if ($addpromo) {


                $request->session()->flash('alert-success',"Entry updated successfully");
                return redirect('offline/list');
            } else {
                $request->session()->flash('alert-success',"Error updating entry");
                return redirect('offline/edit/' . $request->get('id'))->withInput();
            }
        }

    /**
     * generate the excel sheet
     * */
    public function generateExcel($data)
    {
        $appData = array();
        foreach ($data as $requestData) {
            $row['Date'] = date("d-m-Y", strtotime($requestData->payment_date));
            $row['Invoice No'] = $requestData->invoice_no;
            $row['Name'] = $requestData->name;
            $row['Email'] = $requestData->email;
            $row['Mobile'] = $requestData->mobile;
            $row['GSTN'] = $requestData->gstn;
            $row['HSN/SAC'] = $requestData->hsn;
            $row['Voucher'] = $requestData->voucher_code;
            $row['Number Of Voucher'] = $requestData->number_of_voucher;
            $row['Transaction Id'] = $requestData->transaction_id;
            $row['Before GST'] =  $requestData->rate_before_gst;
            $row['SGST'] = $requestData->sgst;
            $row['CGST'] = $requestData->cgst;
            $row['IGST'] = $requestData->igst;
            $row['After GST'] = $requestData->rate_after_gst;
            $row['Payment Type'] = $requestData->payment_type;
            $row['State'] =  $requestData->state_name;
            $appData[] = $row;
        }

        if (!empty($appData)) {

            $file_name = rand();
            $storage_path = Excel::create($file_name, function($excel) use($appData) {
                $excel->sheet('Sheet 1', function($sheet) use($appData) {
                    $sheet->fromArray($appData);
                });
            })->store('xls',false,true);
            return $storage_path;
        }

        return false;

    }

    public function getAllAgent(request $request)
    {
        $requestData = $request->all();
        if(!empty($requestData['term'])) {
            $material = $this->offlinePayment->getAllAgent($requestData['term']);

            $data=array();
            foreach ($material as $product) {
                $data[]=array('value'=>$product->name,'id'=>$product->id);
            }
            if(count($data))
                return $data;
            else
                return ['value'=>'No Result Found','id'=>''];
        }
    }

    /**
     * Delete the specified agent in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function sendMail(request $request)
    {
        $request_data = $request->all();
        $id = $request_data['id'];
        $date = Carbon::yesterday()->format('Y-m-d');
        $online = $this->offlinePayment->getOfflinePaymentByField($id, 'id');

        if(!empty($online)) {
            if(count($online) > 0) {
                $folder_name = date('Y-m-d', strtotime(trim($date)));
                $filepath = public_path(). DIRECTORY_SEPARATOR.'attachment/offline/'.$folder_name;
                if (!file_exists($filepath)) {
                    mkdir($filepath,0777,true);
                }

                if(file_exists($filepath.'/'.$online->invoice_no.'.pdf')){
                    $fileFullPath = $filepath.'/'.$online->invoice_no.'.pdf';
                    $this->deleteFilesIfExist($fileFullPath);
                }
                if(file_exists($filepath.'/'.$online->invoice_no.'.pdf')){
                    $fileFullPath = $filepath.'/'.$online->invoice_no.'.pdf';
                    $this->deleteFilesIfExist($fileFullPath);
                }
                $data['rate_before_gst'] = $online->amount_paid * 100/118;
                $IGST = $online->amount_paid -  $data['rate_before_gst'];
                if($online->state_id == 5){
                    $cgstSgst = $IGST/2;
                    $data['cgst'] = $data['sgst'] = number_format($cgstSgst,2);
                    $data['igst'] = 0;
                }else {
                    $data['cgst'] = $data['sgst'] = 0;
                    $data['igst'] = number_format($IGST,2);
                }
                $data['word_amount'] = $this->getIndianCurrency($online->amount_paid);
                $data['amount_paid'] = $online->amount_paid;
                $data['created_at'] = date("d-m-Y", strtotime($online->created_at));
                $data['name'] = $online->name;
                $data['email'] = $online->email;
                $data['mobile'] = $online->mobile;
                $data['state_name'] = $online->state_name;
                //$data['voucher_code'] = $online->voucher_code;
                $data['voucher_code'] = str_replace(',', '<br />', $online->voucher_code);
                $data['invoice_number'] = $online->invoice_no;
                $data['word_amount'] = $this->getIndianCurrency($online->amount_paid);

                $pdf = PDF::loadView('emails.invoice', $data);
                $pdf->save($filepath.'/'.$online->invoice_no.'.pdf');
                //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                $filename = $filepath.'/'.$online->invoice_no.'.pdf';
                $customer_email_data = [];
                $customer_email_data['email'] = $online->email;
                $customer_email_data['file_path'] = $filename;
                Mail ::send(new InvoiceMail($customer_email_data));
                sleep(2);
            }
            return [
                'statusCode' => 1
            ];
        }else {
            return [
                'statusCode' => 0
            ];
        }

    }

    /**
     * Check and delete files
     *
     * */

    public function deleteFilesIfExist($filePath)
    {
        return Storage::delete($filePath);
    }

    function getIndianCurrency($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }
}
