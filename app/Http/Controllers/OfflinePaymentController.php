<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OfflinePayment;
use Validator;
use DB;


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
        $data['addExistingAgentPaymentTab'] = "active";
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
        $data['agentData'] = $this->offlinePayment->getCollection();
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
        $offlinePaymentData = $offlinePaymentData->GetOfflinePaymentData($request);
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
            $row[] = $offlinePaymentData->state_name;
            $row[] = view('datatable.action', ['module' => "offline",'type' => $offlinePaymentData->id, 'id' => $offlinePaymentData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $offlinePaymentCount,
            'recordsFiltered' => $offlinePaymentCount,
            'data' => $appData,
        ];
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

}
