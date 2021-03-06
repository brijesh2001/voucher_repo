<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OverseasPayment;
use Validator;
use DB;
use Excel;
use PDF;


class OverseasPaymentController extends Controller
{

    protected $overseasPayment;
    public $state;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OverseasPayment $overseasPayment,State $state)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->overseasPayment = $overseasPayment;
        $this->state = $state;

    }

    /**
     * Display a listing of the overseasPayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/OverseasPayment
         *
         * @return mixed
         */
        $data['overseasPaymentData'] = $this->overseasPayment->getCollection();
        $data['overseasPaymentManagementTab'] = "active open";
        $data['offlineOverseasPaymentTab'] = "active";
        return view('overseaspayment.existing_agent_list', $data);
    }

    /**
     * Display a listing of the overseasPayment.
     *
     * @return \Illuminate\Http\Response
     */
    public function addNewAgentPayment()
    {

        /**
         * getCollection from App/Models/OverseasPayment
         *
         * @return mixed
         */
        //$data['overseasPaymentData'] = $this->overseasPayment->getCollection();
        $data['overseasPaymentManagementTab'] = "active open";
        $data['state'] = $this->state->getCollection();
        $data['addNewOverseasPaymentTab'] = "active";
        return view('overseaspayment.add_new_agent_payment', $data);
    }


    public function datatable(Request $request)
    {
        // default count of overseasPayment $overseasPaymentCount
        $overseasPaymentCount = 0;

        /**
         * getDatatableCollection from App/Models/OverseasPayment
         * get all overseasPayments
         *
         * @return mixed
         */
        $overseasPaymentData = $this->overseasPayment->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/OverseasPayment
         * get filterred overseasPayments
         *
         * @return mixed
         */
        $overseasPaymentData = $overseasPaymentData->GetFilteredData($request);

        /**
         * getOverseasPaymentCount from App/Models/OverseasPayment
         * get count of overseasPayments
         *
         * @return integer
         */
        $overseasPaymentCount = $this->overseasPayment->getOverseasPaymentCount($overseasPaymentData);

        // Sorting overseasPayment data base on requested sort order
        if (isset(config('constant.overseasPaymentDataTableFieldArray')[$request->order['0']['column']])) {
            $overseasPaymentData = $overseasPaymentData->SortOverseasPaymentData($request);
        } else {
            $overseasPaymentData = $overseasPaymentData->SortDefaultDataByRaw('tbl_overseas_payment.id', 'desc');
        }

        /**
         * get paginated collection of overseasPayment
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $overseasPaymentData = $overseasPaymentData->GetOverseasPaymentData($request);
        }else {
            $excelraw = $overseasPaymentData->GetFilteredOverseasPaymentData();
            $overseasPaymentData = $overseasPaymentData->GetFilteredOverseasPaymentData($request);
            $file_name =  $this->generateExcel($excelraw);
            if(!empty($file_name)) {
                $url = $file_name['file'];
            }

        }
        $appData = array();
        foreach ($overseasPaymentData as $overseasPaymentData) {
            $row = array();
            $row[] = date("d-m-Y", strtotime($overseasPaymentData->payment_date));
            $row[] = $overseasPaymentData->invoice_no;
            $row[] = $overseasPaymentData->name;
            $row[] = $overseasPaymentData->email;
            $row[] = $overseasPaymentData->mobile;
            $row[] = $overseasPaymentData->lut;
            $row[] = $overseasPaymentData->item;
            $row[] = $overseasPaymentData->dollor_amount_paid;
            $row[] = $overseasPaymentData->amount;
            $row[] = $overseasPaymentData->transaction_id;
            $row[] = $overseasPaymentData->payment_type;
            $row[] = view('datatable.action', ['module' => "overseas",'type' => $overseasPaymentData->id, 'id' => $overseasPaymentData->id])->render();
            $row[] = view('datatable.overseas_pdf', ['module' => "agent",'type' => 'overseas', 'id' => $overseasPaymentData->id])->render();
            $appData[] = $row;
        }

        $return_data =  [
            'draw' => $request->draw,
            'recordsTotal' => $overseasPaymentCount,
            'recordsFiltered' => $overseasPaymentCount,
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
                'amount' => 'required',
                'dollor_amount_paid' => 'required',
                'dollor_amount_received' => 'required',
                'payment_date' => 'required',

            );
        }
            if ($mode == 'edit-agent') {
                $rules = array(

                    'name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required',
                    'item' => 'required',
                    'amount' => 'required',
                    'dollor_amount_paid' => 'required',
                    'dollor_amount_received' => 'required',
                    'payment_date' => 'required',
                );
            }


            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                $errorRedirectUrl = "overseas/add-new-agent";
                if ($mode == "add-new-agent") {
                    $errorRedirectUrl = "overseas/add-new-agent/";
                }
                if ($mode == "edit-agent") {
                    $errorRedirectUrl = "overseas/edit/" . $data['id'];
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
                $addagent = $this->overseasPayment->storeNewAgentPayment($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('overseas/add-new-agent')->withInput();

            }
            if ($addagent) {
                //Event::fire(new SendMail($addprize));
                $request->session()->flash('alert-success', __('app.default_add_success', ["module" => __('app.overseas_payment_managment')]));
                return redirect('overseas/list');
            } else {
                $request->session()->flash('alert-danger', __('app.default_error', ["module" => __('app.overseas_payment_managment'), "action" => __('app.add')]));
                return redirect('overseas/add-new-agent')->withInput();
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
            $deletePromo = $this->overseasPayment->deleteAgent($request->id);
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
            $data['details'] = $this->overseasPayment->getOverseasPaymentByField($id, 'id');
            $data['agentData'] = $this->overseasPayment->getCollection();
            $data['state'] = $this->state->getCollection();
            $data['overseasPaymentManagementTab'] = "active open";
            $data['addExistingAgentPaymentTab'] = "active";
            return view('overseaspayment.edit_existing_agent_payment', $data);
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
                $addpromo = $this->overseasPayment->updateAgent($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q=' . $e->getMessage() . '">' . $e->getMessage() . '</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('overseas/edit/' . $request->get('id'))->withInput();

            }

            if ($addpromo) {


                $request->session()->flash('alert-success',"Entry updated successfully");
                return redirect('overseas/list');
            } else {
                $request->session()->flash('alert-success',"Error updating entry");
                return redirect('overseas/edit/' . $request->get('id'))->withInput();
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
            $row['LUT'] = $requestData->lut;
            $row['Item'] = $requestData->item;
            $row['Package Detail'] = $requestData->detail;
            $row['Transaction Id'] = $requestData->transaction_id;
            $row['Dollar Amount paid'] = $requestData->dollor_amount_paid;
            $row['Dollar Amount received'] = $requestData->dollor_amount_received;
            $row['Rs Amount'] =  $requestData->amount;
            $row['Country'] =  $requestData->country;
            $row['Address'] =  $requestData->address;
            $row['Payment Type'] = $requestData->payment_type;
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
            $material = $this->overseasPayment->getAllAgent($requestData['term']);
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
     * @param Request $request
     * @return mixed
     * @desc download pdf
     */
    public function downloadpdf(request $request)
    {
        $requestData = $request->all();
        if(!empty($requestData)) {
                $id = $requestData['id'];
                $data = (array)$this->overseasPayment->getOverseasPaymentForPdfDownload($id);
                if(!empty($data)) {
                    //typecasting the variable like above array for pdf download
                    $data['created_at'] = date("d-m-Y", strtotime($data['payment_date']));
                    //$data['created_at '] = $data['payment_date'];
                    $data['invoice_number'] = $data['invoice_no'];
                    $data['word_amount'] = $this->getIndianCurrency($data['dollor_amount_paid']);
                    $data['amount'] = $data['dollor_amount_paid'];
                    $data['state_name'] = $data['country'];
                    $data['voucher_code'] = $data['item'];
                    $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                    if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                        $pdf = PDF::loadView('emails.overseas_invoice', $data);
                    }else{
                        $pdf = PDF::loadView('emails.new_overseas_invoice', $data);
                    }
                    //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                    return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                }

        }
    }

    function getIndianCurrency($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
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
        $paise = ($decimal) ? "and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' cent' : '';
        return ($Rupees ? $Rupees . 'Dollars ' : '') . $paise;
    }

}
