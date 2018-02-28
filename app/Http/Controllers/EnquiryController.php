<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Role;
use Excel;
use Carbon\Carbon;

class EnquiryController extends Controller
{

    protected $enquiry;
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Enquiry $enquiry, Role $role)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->enquiry = $enquiry;
        $this->role = $role;
    }

    /**
     * Display a listing of the enquiry.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/Enquiry
         *
         * @return mixed
         */
        $data['enquiryData'] = $this->enquiry->getCollection();
        $data['enquiryManagementTab'] = "active open";
        $data['enquiryTab'] = "active";
        return view('enquiry.enquirylist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of enquiry $enquiryCount
        $enquiryCount = 0;

        /**
         * getDatatableCollection from App/Models/Enquiry
         * get all enquirys
         *
         * @return mixed
         */
        $enquiryData = $this->enquiry->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/Enquiry
         * get filterred enquirys
         *
         * @return mixed
         */
        $enquiryData = $enquiryData->GetFilteredData($request);

        /**
         * getEnquiryCount from App/Models/Enquiry
         * get count of enquirys
         *
         * @return integer
         */
        $enquiryCount = $this->enquiry->getEnquiryCount($enquiryData);

        // Sorting enquiry data base on requested sort order
        if (isset(config('constant.enquiryDataTableFieldArray')[$request->order['0']['column']])) {
            $enquiryData = $enquiryData->SortEnquiryData($request);
        } else {
            $enquiryData = $enquiryData->SortDefaultDataByRaw('tbl_enquiry.id', 'desc');
        }

        /**
         * get paginated collection of enquiry
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */

        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $enquiryData = $enquiryData->GetEnquiryData($request);
        }else {
            $excelraw = $enquiryData->GetFilteredEnquiryData();
            $enquiryData = $enquiryData->GetFilteredEnquiryData();
           $file_name =  $this->generateExcel($excelraw);
           if(!empty($file_name)) {
            $url = $file_name['file'];
           }

        }

                //scopeGetFilteredEnquiryData

        $appData = array();
        foreach ($enquiryData as $enquirysData) {
            $row = array();
            $row[] = $enquirysData->email;
            $row[] = $enquirysData->name;
            $row[] = $enquirysData->mobile;
            $row[] = $enquirysData->number_of_voucher;
            $row[] = $enquirysData->rate;
            $row[] = $enquirysData->payment_request_id;
            $row[] = date("d-m-Y H:i:s", strtotime($enquirysData->created_at));
            $appData[] = $row;
        }

            $return_data = [
                'draw' => $request->draw,
                'recordsTotal' => $enquiryCount,
                'recordsFiltered' => $enquiryCount,
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
            $row['Name'] = $requestData->name;
            $row['Mobile'] = $requestData->mobile;
            $row['Number Of Voucher'] = $requestData->number_of_voucher;
            $row['Rate'] = $requestData->rate;
            $row['Payment Request Id'] = $requestData->payment_request_id;
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


}
