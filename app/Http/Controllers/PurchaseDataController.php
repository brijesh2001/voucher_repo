<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PurchaseData;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Excel;

class PurchaseDataController extends Controller
{

    protected $purchasedata;
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PurchaseData $purchasedata, Role $role)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->purchasedata = $purchasedata;
        $this->role = $role;
    }

    /**
     * Display a listing of the purchasedata.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/PurchaseData
         *
         * @return mixed
         */
        $data['purchasedataData'] = $this->purchasedata->getCollection();
        $data['purchasedataManagementTab'] = "active open";
        $data['purchasedataTab'] = "active";
        return view('purchasedata.purchasedatalist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of purchasedata $purchasedataCount
        $purchasedataCount = 0;

        /**
         * getDatatableCollection from App/Models/PurchaseData
         * get all purchasedatas
         *
         * @return mixed
         */
        $purchasedataData = $this->purchasedata->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/PurchaseData
         * get filterred purchasedatas
         *
         * @return mixed
         */
        $purchasedataData = $purchasedataData->GetFilteredData($request);

        /**
         * getPurchaseDataCount from App/Models/PurchaseData
         * get count of purchasedatas
         *
         * @return integer
         */
        $purchasedataCount = $this->purchasedata->getPurchaseDataCount($purchasedataData);

        // Sorting purchasedata data base on requested sort order
        if (isset(config('constant.purchasedataDataTableFieldArray')[$request->order['0']['column']])) {
            $purchasedataData = $purchasedataData->SortPurchaseDataData($request);
        } else {
            $purchasedataData = $purchasedataData->SortDefaultDataByRaw('tbl_purchase_data.received_date', 'desc');
        }

        /**
         * get paginated collection of purchasedata
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */

        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $purchasedataData = $purchasedataData->GetPurchaseDataData($request);
        }else {
            $excelraw = $purchasedataData->GetFilteredPurchaseData();
            $purchasedataData = $purchasedataData->GetFilteredPurchaseData();
            $file_name =  $this->generateExcel($excelraw);
            if(!empty($file_name)) {
                $url = $file_name['file'];
            }

        }
        $appData = array();
        $i = 1;
        foreach ($purchasedataData as $purchasedataData) {
            $row = array();
            $row[] = $i;
            $row[] = date("d-m-Y", strtotime($purchasedataData->purchase_date));
            $row[] = date("d-m-Y", strtotime($purchasedataData->received_date));
            $row[] = $purchasedataData->invoice_number;
            $row[] = $purchasedataData->rtgs;
            $row[] = $purchasedataData->narration;
            $row[] = $purchasedataData->quantity;
            $row[] = $purchasedataData->per_voucher_prize;
            $row[] = $purchasedataData->total_amount;
            $row[] = view('datatable.action', ['module' => "purchase",'type' => $purchasedataData->id, 'id' => $purchasedataData->id])->render();
            $appData[] = $row;
            $i++;
        }

        $return_data = [
            'draw' => $request->draw,
            'recordsTotal' => $purchasedataCount,
            'recordsFiltered' => $purchasedataCount,
            'data' => $appData,


        ];

        if($request['filterExport']['export_excel'] == 1) {
            $return_data['url'] = $url;
        }
        return $return_data;
    }

    /**
     * generate the excel sheet
     * */
    public function generateExcel($data)
    {
        $appData = array();
        foreach ($data as $requestData) {
            $row['Invoice Date'] = date("d-m-Y", strtotime($requestData->purchase_date));
            $row['Payment Made Date'] = date("d-m-Y", strtotime($requestData->received_date));
            $row['Invoice Number'] = $requestData->invoice_number;
            $row['RTGS Number'] = $requestData->rtgs;
            $row['Narration'] = $requestData->narration;
            $row['Quantity'] = $requestData->quantity;
            $row['Per Voucher Prize'] = $requestData->per_voucher_prize;
            $row['Total Amount'] = $requestData->total_amount;
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

    /**
     * Show the form for creating a new purchasedata.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['purchasedataManagementTab'] = "active open";
        $data['purchasedataTab'] = "active";
        $data['purchasedataData'] = $this->purchasedata->getCollection();
        $data['roleData'] = $this->role->getCollection();
        return view('purchasedata.add', $data);
    }

    /**
     * Display the specified purchasedata.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        /**
         * get details of the specified purchasedata. from App/Models/PurchaseData
         *
         * @param mixed $id
         * @param string (id) fieldname
         * @return mixed
         */
        $data['details'] = $this->purchasedata->getPurchaseDataByField($id, 'id');
        $data['purchasedataData'] = $this->purchasedata->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['purchasedataTab'] = "active";
        return view('purchasedata.edit', $data);
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
        $rules = array(
            'purchase_date' => 'required',
            'received_date' => 'required',
            'invoice_number' => 'required',
            'quantity' => 'required',
            'per_voucher_prize' => 'required',
            'total_amount' => 'required',
            'rtgs' => 'required',
            'narration' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "purchase/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "purchase/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created purchasedata in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {

        $validations = $this->customeValidate($request->all(), 'add');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $addpurchasedata = $this->purchasedata->addPurchaseData($request->all());

            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('purchase/add')->withInput();

        }
        if ($addpurchasedata) {
            //Event::fire(new SendMail($addpurchasedata));
            $request->session()->flash('alert-success','Entry added successfully');
            return redirect('purchase/list');
        } else {
            $request->session()->flash('alert-danger','Error occured while adding entry');
            return redirect('purchase/add')->withInput();
        }
    }

    /**
     * Update the specified purchasedata in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'edit');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $addpurchasedata = $this->purchasedata->addPurchaseData($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('purchase/edit/' . $request->get('id'))->withInput();

        }

        if ($addpurchasedata) {


            $request->session()->flash('alert-success','Entry edited successfully');
            return redirect('purchase/list');
        } else {
            $request->session()->flash('alert-danger','Error occured while adding entry');
            return redirect('purchase/edit/' . $request->get('id'))->withInput();
        }
    }


    /**
     * Delete the specified purchasedata in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deletePurchaseData = $this->purchasedata->deletePurchaseData($request->id);
        if ($deletePurchaseData) {
            $request->session()->flash('alert-success',"Entry deleted successfully");
        } else {
            $request->session()->flash('alert-danger','Error occured while deleting entry');
        }
        echo 1;
    }



}
