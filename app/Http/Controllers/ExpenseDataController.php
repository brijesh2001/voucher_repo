<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExpenseData;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Excel;

class ExpenseDataController extends Controller
{

    protected $expensedata;
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ExpenseData $expensedata, Role $role)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->expensedata = $expensedata;
        $this->role = $role;
    }

    /**
     * Display a listing of the expensedata.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/ExpenseData
         *
         * @return mixed
         */
        $data['expensedataData'] = $this->expensedata->getCollection();
        $data['purchasedataManagementTab'] = "active open";
        $data['expensedataTab'] = "active";
        return view('expensedata.expensedatalist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of expensedata $expensedataCount
        $expensedataCount = 0;

        /**
         * getDatatableCollection from App/Models/ExpenseData
         * get all expensedatas
         *
         * @return mixed
         */
        $expensedataData = $this->expensedata->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/ExpenseData
         * get filterred expensedatas
         *
         * @return mixed
         */
        $expensedataData = $expensedataData->GetFilteredData($request);

        /**
         * getExpenseDataCount from App/Models/ExpenseData
         * get count of expensedatas
         *
         * @return integer
         */
        $expensedataCount = $this->expensedata->getExpenseDataCount($expensedataData);

        // Sorting expensedata data base on requested sort order
        if (isset(config('constant.expensedataDataTableFieldArray')[$request->order['0']['column']])) {
            $expensedataData = $expensedataData->SortExpenseDataData($request);
        } else {
            $expensedataData = $expensedataData->SortDefaultDataByRaw('tbl_expense_data.date', 'desc');
        }

        /**
         * get paginated collection of expensedata
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */

        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $expensedataData = $expensedataData->GetExpenseDataData($request);
        }else {
            $excelraw = $expensedataData->GetFilteredExpenseData();
            $expensedataData = $expensedataData->GetFilteredExpenseData();
            $file_name =  $this->generateExcel($excelraw);
            if(!empty($file_name)) {
                $url = $file_name['file'];
            }

        }

        $appData = array();
        $i = 1;
        foreach ($expensedataData as $expensedataData) {
            $row = array();
            $row[] = $i;
            $row[] = date("d-m-Y", strtotime($expensedataData->invoice_date));
            $row[] = date("d-m-Y", strtotime($expensedataData->date));
            $row[] = $expensedataData->invoice_number;
            $row[] = $expensedataData->name;
            $row[] = $expensedataData->detail;
            $row[] = $expensedataData->gstn;
            $row[] = $expensedataData->hsn_sac ;
            $row[] = $expensedataData->before_gst;
            $row[] = $expensedataData->gst;
            $row[] = $expensedataData->sgst;
            $row[] = $expensedataData->cgst;
            $row[] = $expensedataData->after_gst;
            $row[] = view('datatable.action', ['module' => "expense",'type' => $expensedataData->id, 'id' => $expensedataData->id])->render();
            $appData[] = $row;
            $i++;
        }

        $return_data =  [
            'draw' => $request->draw,
            'recordsTotal' => $expensedataCount,
            'recordsFiltered' => $expensedataCount,
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
            $row['Invoice Date'] = date("d-m-Y", strtotime($requestData->invoice_date));
            $row['Payment Date'] = date("d-m-Y", strtotime($requestData->date));
            $row['Invoice No'] = $requestData->invoice_number;
            $row['Name'] = $requestData->name;
            $row['Narration'] = $requestData->detail;
            $row['GSTN'] = $requestData->gstn;
            $row['HSN/SAC'] = $requestData->hsn_sac ;
            $row['Before GST'] = $requestData->before_gst;
            $row['IGST'] = $requestData->gst;
            $row['SGST'] = $requestData->sgst;
            $row['CGST'] = $requestData->cgst;
            $row['After GST'] = $requestData->after_gst;
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
     * Show the form for creating a new expensedata.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['expensedataManagementTab'] = "active open";
        $data['expensedataTab'] = "active";
        $data['expensedataData'] = $this->expensedata->getCollection();
        $data['roleData'] = $this->role->getCollection();
        return view('expensedata.add', $data);
    }

    /**
     * Display the specified expensedata.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        /**
         * get details of the specified expensedata. from App/Models/ExpenseData
         *
         * @param mixed $id
         * @param string (id) fieldname
         * @return mixed
         */
        $data['details'] = $this->expensedata->getExpenseDataByField($id, 'id');
        $data['expensedataData'] = $this->expensedata->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['expensedataTab'] = "active";
        return view('expensedata.edit', $data);
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
            'date' => 'required',
            'name' => 'required',
            'detail' => 'required',
            'before_gst' => 'required',
            'gst' => 'required',
            'sgst' => 'required',
            'cgst' => 'required',
            'after_gst' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "expense/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "expense/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created expensedata in storage.
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
            $addexpensedata = $this->expensedata->addExpenseData($request->all());

            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('expense/add')->withInput();

        }
        if ($addexpensedata) {
            //Event::fire(new SendMail($addexpensedata));
            $request->session()->flash('alert-success','Entry added successfully');
            return redirect('expense/list');
        } else {
            $request->session()->flash('alert-danger','Error occured while adding entry');
            return redirect('expense/add')->withInput();
        }
    }

    /**
     * Update the specified expensedata in storage.
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
            $addexpensedata = $this->expensedata->addExpenseData($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('expense/edit/' . $request->get('id'))->withInput();

        }

        if ($addexpensedata) {


            $request->session()->flash('alert-success','Entry edited successfully');
            return redirect('expense/list');
        } else {
            $request->session()->flash('alert-danger','Error occured while adding entry');
            return redirect('expense/edit/' . $request->get('id'))->withInput();
        }
    }


    /**
     * Delete the specified expensedata in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deleteExpenseData = $this->expensedata->deleteExpenseData($request->id);
        if ($deleteExpenseData) {
            $request->session()->flash('alert-success',"Entry deleted successfully");
        } else {
            $request->session()->flash('alert-danger','Error occured while deleting entry');
        }
        echo 1;
    }



}
