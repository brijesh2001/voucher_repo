<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refer;
use App\Models\Role;
use Excel;
use Validator;
use DB;

class ReferController extends Controller
{

    protected $refer;
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Refer $refer, Role $role)
    {
        $this->refer = $refer;
        $this->role = $role;
    }

    /**
     * Display a listing of the refer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/Refer
         *
         * @return mixed
         */
        $data['referData'] = $this->refer->getCollection();
        $data['referManagementTab'] = "active open";
        $data['referTab'] = "active";
        return view('refer.referlist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of refer $referCount
        $referCount = 0;

        /**
         * getDatatableCollection from App/Models/Refer
         * get all refers
         *
         * @return mixed
         */
        $referData = $this->refer->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/Refer
         * get filterred refers
         *
         * @return mixed
         */
        $referData = $referData->GetFilteredData($request);

        /**
         * getReferCount from App/Models/Refer
         * get count of refers
         *
         * @return integer
         */
        $referCount = $this->refer->getReferCount($referData);

        // Sorting refer data base on requested sort order
        if (isset(config('constant.referDataTableFieldArray')[$request->order['0']['column']])) {
            $referData = $referData->SortReferData($request);
        } else {
            $referData = $referData->SortDefaultDataByRaw('tbl_refer.id', 'desc');
        }

        /**
         * get paginated collection of refer
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */

        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $referData = $referData->GetReferData($request);
        }else {
            $excelraw = $referData->GetFilteredReferData();
            $referData = $referData->GetFilteredReferData();
           $file_name =  $this->generateExcel($excelraw);
           if(!empty($file_name)) {
            $url = $file_name['file'];
           }

        }
            //scopeGetFilteredReferData

        $appData = array();
        foreach ($referData as $refersData) {
            $row = array();
            $row[] = $refersData->email;
            $row[] = date("d-m-Y H:i:s", strtotime($refersData->created_at));
            $appData[] = $row;
        }

            $return_data = [
                'draw' => $request->draw,
                'recordsTotal' => $referCount,
                'recordsFiltered' => $referCount,
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
            $row['Email'] = $requestData->email;
            $row['Date'] =  date("d-m-Y H:i:s", strtotime($requestData->created_at));;
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

    /*
         * Download the file
         * */

    public function downloadfile(Request $request)
    {

        $file_name =  $request->file_name;
        $path = storage_path('exports/'.$file_name);
        return response()->download($path);
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
            'email' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "refer-friend";

            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

   /*
    * @desc to store the friend
    */
    public function store(request $request)
    {
        
        // Start Communicate with database
        DB::beginTransaction();
        try{
            $addprize = $this->refer->addRefer($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $request->session()->flash('alert-danger', 'Something went wrong please try again !');
            return redirect('refer-friend')->withInput();
        }
        if ($addprize) {
            return redirect('/thankyourefer');
        } else {
            $request->session()->flash('alert-danger', 'Something went wrong please try again !');
            return redirect('refer-friend')->withInput();
        }
    }

}
