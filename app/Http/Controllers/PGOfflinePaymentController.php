<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PGOfflinePayment;
use Validator;
use DB;
use Excel;
use App\Mail\InvoiceMail;
use Mail;
use Carbon\Carbon;
use Storage;
use PDF;


class PGOfflinePaymentController extends Controller
{

    protected $pgofflinePayment;
    public $state;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PGOfflinePayment $pgofflinePayment,State $state)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->pgofflinePayment = $pgofflinePayment;
        $this->state = $state;

    }

    /**
     * Display a listing of the pgofflinePayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/PGOfflinePayment
         *
         * @return mixed
         */
        $data['pgofflinePaymentData'] = $this->pgofflinePayment->getCollection();
        $data['PGOfflinePaymentManagementTab'] = "active open";
        $data['pgofflineAgentPaymentTab'] = "active";
        return view('pgofflinepayment.existing_agent_list', $data);
    }

    /**
     * Display a listing of the pgofflinePayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function addNewAgentPayment()
    {

        /**
         * getCollection from App/Models/PGOfflinePayment
         *
         * @return mixed
         */
        //$data['pgofflinePaymentData'] = $this->pgofflinePayment->getCollection();
        $data['PGOfflinePaymentManagementTab'] = "active open";
        $data['state'] = $this->state->getCollection();
        $data['addNewPGPaymentTab'] = "active";
        return view('pgofflinepayment.add_new_agent_payment', $data);
    }



    public function datatable(Request $request)
    {
        // default count of pgofflinePayment $pgofflinePaymentCount
        $pgofflinePaymentCount = 0;

        /**
         * getDatatableCollection from App/Models/PGOfflinePayment
         * get all pgofflinePayments
         *
         * @return mixed
         */
        $pgofflinePaymentData = $this->pgofflinePayment->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/PGOfflinePayment
         * get filterred pgofflinePayments
         *
         * @return mixed
         */
        $pgofflinePaymentData = $pgofflinePaymentData->GetFilteredData($request);

        /**
         * getPGOfflinePaymentCount from App/Models/PGOfflinePayment
         * get count of pgofflinePayments
         *
         * @return integer
         */
        $pgofflinePaymentCount = $this->pgofflinePayment->getPGOfflinePaymentCount($pgofflinePaymentData);

        // Sorting pgofflinePayment data base on requested sort order
        if (isset(config('constant.pgofflinePaymentDataTableFieldArray')[$request->order['0']['column']])) {
            $pgofflinePaymentData = $pgofflinePaymentData->SortPGOfflinePaymentData($request);
        } else {
            $pgofflinePaymentData = $pgofflinePaymentData->SortDefaultDataByRaw('tbl_pgoffline_payment.id', 'desc');
        }

        /**
         * get paginated collection of pgofflinePayment
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $pgofflinePaymentData = $pgofflinePaymentData->GetPGOfflinePaymentData($request);
        }else {
            $excelraw = $pgofflinePaymentData->GetFilteredPGOfflinePaymentData();
            $pgofflinePaymentData = $pgofflinePaymentData->GetFilteredPGOfflinePaymentData($request);
            $file_name =  $this->generateExcel($excelraw);
            if(!empty($file_name)) {
                $url = $file_name['file'];
            }

        }
        $appData = array();
        foreach ($pgofflinePaymentData as $pgofflinePaymentData) {
            $row = array();
            $row[] = date("d-m-Y", strtotime($pgofflinePaymentData->payment_date));
            $row[] = $pgofflinePaymentData->invoice_no;
            $row[] = $pgofflinePaymentData->name;
            $row[] = $pgofflinePaymentData->email;
            $row[] = $pgofflinePaymentData->mobile;
            $row[] = $pgofflinePaymentData->gstn;
            $row[] = $pgofflinePaymentData->hsn;
            $row[] = $pgofflinePaymentData->item;
            $row[] = $pgofflinePaymentData->detail;
            $row[] = $pgofflinePaymentData->transaction_id;
            $row[] = $pgofflinePaymentData->rate_before_gst;
            $row[] = $pgofflinePaymentData->sgst;
            $row[] = $pgofflinePaymentData->cgst;
            $row[] = $pgofflinePaymentData->igst;
            $row[] = $pgofflinePaymentData->rate_after_gst;
            $row[] = $pgofflinePaymentData->payment_type;
            $row[] = $pgofflinePaymentData->state_name;
            $row[] = view('datatable.action', ['module' => "pgoffline",'type' => $pgofflinePaymentData->id, 'id' => $pgofflinePaymentData->id])->render();
            $row[] = view('datatable.pdf', ['module' => "agent",'type' => 'pgoffline', 'id' => $pgofflinePaymentData->id])->render();
            $row[] = view('datatable.send', ['module' => "agent",'type' => $pgofflinePaymentData->id, 'id' => $pgofflinePaymentData->id])->render();
            $appData[] = $row;
        }

        $return_data =  [
            'draw' => $request->draw,
            'recordsTotal' => $pgofflinePaymentCount,
            'recordsFiltered' => $pgofflinePaymentCount,
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
                'item' => 'required',
                'detail' => 'required',
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
                    'item' => 'required',
                    'detail' => 'required',
                    'rate_after_gst' => 'required',
                    'state' => 'required',
                    'payment_date' => 'required',
                    'transaction_id' => 'required',
                );
            }
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                $errorRedirectUrl = "pgoffline/add-new-agent";
                if ($mode == "add-new-agent") {
                    $errorRedirectUrl = "pgoffline/add-new-agent/";
                }
                if ($mode == "edit-agent") {
                    $errorRedirectUrl = "pgoffline/edit/" . $data['id'];
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
                $addagent = $this->pgofflinePayment->storeNewAgentPayment($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('pgoffline/add-new-agent')->withInput();

            }
            if ($addagent) {
                //Event::fire(new SendMail($addprize));
                $request->session()->flash('alert-success', __('app.default_add_success', ["module" => __('app.pgoffline_payment_managment')]));
                return redirect('pgoffline/list');
            } else {
                $request->session()->flash('alert-danger', __('app.default_error', ["module" => __('app.pgoffline_payment_managment'), "action" => __('app.add')]));
                return redirect('pgoffline/add-new-agent')->withInput();
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
            $deletePromo = $this->pgofflinePayment->deleteAgent($request->id);
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
            $data['details'] = $this->pgofflinePayment->getPGOfflinePaymentByField($id, 'id');
            $data['agentData'] = $this->pgofflinePayment->getCollection();
            $data['state'] = $this->state->getCollection();
            $data['pgofflinePaymentManagementTab'] = "active open";
            $data['addExistingAgentPaymentTab'] = "active";
            return view('pgofflinepayment.edit_existing_agent_payment', $data);
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
                $addpromo = $this->pgofflinePayment->updateAgent($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('pgoffline/edit/' . $request->get('id'))->withInput();

            }

            if ($addpromo) {


                $request->session()->flash('alert-success',"Entry updated successfully");
                return redirect('pgoffline/list');
            } else {
                $request->session()->flash('alert-success',"Error updating entry");
                return redirect('pgoffline/edit/' . $request->get('id'))->withInput();
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
            $row['Item'] = $requestData->item;
            $row['Detail'] = $requestData->detail;
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
            $material = $this->pgofflinePayment->getAllAgent($requestData['term']);

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
     * For sending the Invoice to the customer
     *
     * */

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
        $online = $this->pgofflinePayment->getPGOfflinePaymentByField($id, 'id');
        if(!empty($data)) {
            if(count($online) > 0) {
                $folder_name = date('Y-m-d', strtotime(trim($date)));
                $filepath = public_path(). DIRECTORY_SEPARATOR.'attachment/pg/'.$folder_name;
                if (!file_exists($filepath)) {
                    mkdir($filepath,0777,true);
                }

                    if(file_exists($filepath.'/'.$online->invoice_number.'.pdf')){
                        $fileFullPath = $filepath.'/'.$online->invoice_number.'.pdf';
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
                    $data['item'] = str_replace(',', '<br />', $online->item);
                    $data['invoice_number'] = $online->invoice_number;
                    $data['word_amount'] = $this->getIndianCurrency($online->amount_paid);
                    $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                    if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                        $pdf = PDF::loadView('emails.invoice', $data);
                    }else{
                        $pdf = PDF::loadView('emails.new_invoice', $data);
                    }
                    $pdf->save($filepath.'/'.$online->invoice_number.'.pdf');
                    //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                    $filename = $filepath.'/'.$online->invoice_number.'.pdf';
                    $customer_email_data = [];
                    $customer_email_data['email'] = $online->email;
                    $customer_email_data['file_path'] = $filename;
                    Mail ::send(new InvoiceMail($customer_email_data));
                    sleep(2);
            }

            Mail ::send(new InvoiceMail($data));
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
