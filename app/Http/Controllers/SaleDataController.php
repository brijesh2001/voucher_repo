<?php

namespace App\Http\Controllers;

use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use App\Models\SaleData;
use App\Models\Role;
use Validator;
use Event;
use Hash;
use DB;
use Excel;
use PDF;
class SaleDataController extends Controller
{

    protected $saledata;
    protected $role;
    protected $offlinePayment;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SaleData $saledata, Role $role,OfflinePayment $offlinePayment)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->saledata = $saledata;
        $this->role = $role;
        $this->offlinePayment = $offlinePayment;
    }

    /**
     * Display a listing of the saledata.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/SaleData
         *
         * @return mixed
         */
        $data['saledataData'] = $this->saledata->getCollection();
        $data['saledataManagementTab'] = "active open";
        $data['saledataTab'] = "active";
        return view('saledata.saledatalist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of saledata $saledataCount
        $saledataCount = 0;

        /**
         * getDatatableCollection from App/Models/SaleData
         * get all saledatas
         *
         * @return mixed
         */
        $saledataData = $this->saledata->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/SaleData
         * get filterred saledatas
         *
         * @return mixed
         */
        $saledataData = $saledataData->GetFilteredData($request);

        /**
         * getSaleDataCount from App/Models/SaleData
         * get count of saledatas
         *
         * @return integer
         */
        $saledataCount = $this->saledata->getSaleDataCount($saledataData);

        // Sorting saledata data base on requested sort order
        if (isset(config('constant.saledataDataTableFieldArray')[$request->order['0']['column']])) {
            $saledataData = $saledataData->SortSaleDataData($request);
        } else {
            $saledataData = $saledataData->SortDefaultDataByRaw('tbl_sale_data.id', 'desc');
        }

        /**
         * get paginated collection of saledata
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        $saledataData = $saledataData->GetSaleDataData($request);
        $appData = array();
        foreach ($saledataData as $saledataData) {
            $row = array();
            $row[] = date("d-m-Y H:i:s", strtotime($saledataData->created_at));
            $row[] = ($saledataData->Enquiry) ? $saledataData->Enquiry->name : "---";
            $row[] = ($saledataData->Enquiry) ? $saledataData->Enquiry->email : "---";
            $row[] = ($saledataData->Enquiry) ? $saledataData->Enquiry->mobile : "---";
            $row[] = $saledataData->voucher_code;
            $row[] = $saledataData->payment_code;
            $row[] = $saledataData->rate;
            $row[] = $saledataData->amount_paid;
            $row[] = $saledataData->number_of_voucher;
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $saledataCount,
            'recordsFiltered' => $saledataCount,
            'data' => $appData,
        ];
    }

    /**
     * Display a listing of the invoicedata
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceList()
    {

        /**
         * getCollection from App/Models/SaleData
         *
         * @return mixed
         */
        $data['saledataData'] = $this->saledata->getCollection();
        $data['saledataManagementTab'] = "active open";
        $data['invoicedataTab'] = "active";
        return view('saledata.invoicedatalist', $data);
    }
    /**
     * Display a listing of the invoicedata
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceDatatable(Request $request)
    {
        // default count of saledata $saledataCount
        $saledataCount = 0;

        /**
         * getDatatableCollection from App/Models/SaleData
         * get all saledatas
         *
         * @return mixed
         */
        $saledataData = $this->saledata->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/SaleData
         * get filterred saledatas
         *
         * @return mixed
         */
        $saledataData = $saledataData->GetFilteredData($request);

        /**
         * getSaleDataCount from App/Models/SaleData
         * get count of saledatas
         *
         * @return integer
         */
        $saledataCount = $this->saledata->getSaleDataCount($saledataData);

        // Sorting saledata data base on requested sort order
        if (isset(config('constant.invoicedataDataTableFieldArray')[$request->order['0']['column']])) {
            $saledataData = $saledataData->SortSaleDataData($request);
        } else {
            $saledataData = $saledataData->SortDefaultDataByRaw('tbl_sale_data.created_at', 'desc');
        }


        /**
         * get paginated collection of saledata
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        //$saledataData = $saledataData->GetSaleDataData($request);

        $url = '';
        if($request['filterExport']['export_excel'] == 0) {
            $saledataData = $saledataData->GetSaleDataData($request);
        }else {
            $excelraw = $saledataData->GetFilteredSaleData();
            $saledataData = $saledataData->GetSaleDataData($request);
            $file_name =  $this->generateExcel($excelraw);
            if(!empty($file_name)) {
                $url = $file_name['file'];
            }

        }

        $appData = array();
        foreach ($saledataData as $saledataData) {

            $row = array();
            $amount_paid = $saledataData->amount_paid;
            $before_gst = ($amount_paid*100)/118;
            $IGST = $amount_paid - $before_gst;
            $row[] = date("d-m-Y H:i:s", strtotime($saledataData->created_at));
            $row[] = $saledataData->invoice_number;
            $row[] = (isset($saledataData->Enquiry)) ? $saledataData->Enquiry->name : "---";
            $row[] = (isset($saledataData->Enquiry)) ? $saledataData->Enquiry->email : "---";
            $row[] = (isset($saledataData->Enquiry)) ? $saledataData->Enquiry->mobile : "---";
            $row[] = $saledataData->client_gstn;
            $row[] = '8532';
            $row[] = $saledataData->voucher_code;
            $row[] = $saledataData->number_of_voucher;
            $row[] = $saledataData->payment_code;
            $row[] = number_format(($amount_paid*100)/118,2);
            $row[] = (isset($saledataData->Enquiry) && $saledataData->Enquiry->state == 5) ? 'SGST:'.number_format($IGST/2,2) : '-' ;
            $row[] = (isset($saledataData->Enquiry) && $saledataData->Enquiry->state == 5) ? 'CGST:'.number_format($IGST/2,2) : '-' ;
            $row[] = (isset($saledataData->Enquiry) && $saledataData->Enquiry->state == 5) ? '-' :  'IGST:'.number_format($IGST,2);
            $row[] = $saledataData->amount_paid;
            $row[] =  $saledataData->state ;
            $row[] = view('datatable.pdf', ['module' => "agent",'type' => 'online', 'id' => $saledataData->id])->render();

            $appData[] = $row;
        }


        $return_data =  [
            'draw' => $request->draw,
            'recordsTotal' => $saledataCount,
            'recordsFiltered' => $saledataCount,
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
            $amount_paid = $requestData->amount_paid;
            $before_gst = ($amount_paid*100)/118;
            $IGST = $amount_paid - $before_gst;
            $row['Date'] = date("d-m-Y H:i:s", strtotime($requestData->created_at));
            $row['Invoice No'] = $requestData->invoice_number;
            $row['Name'] = (isset($requestData->Enquiry)) ? $requestData->Enquiry->name : "---";
            $row['Email'] = (isset($requestData->Enquiry)) ? $requestData->Enquiry->email : "---";
            $row['Mobile'] = (isset($requestData->Enquiry)) ? $requestData->Enquiry->mobile : "---";
            $row['GSTN'] = $requestData->client_gstn;
            $row['HSN/SAC'] = '8532';
            $row['Voucher'] = $requestData->voucher_code;
            $row['Number Of Voucher'] = $requestData->number_of_voucher;
            $row['Transaction Id'] = $requestData->payment_code;
            $row['Before GST'] =  number_format(($amount_paid*100)/118,2);
            $row['SGST'] = (isset($requestData->Enquiry) && $requestData->Enquiry->state == 5) ? 'SGST:'.number_format($IGST/2,2): '-' ;
            $row['CGST'] = (isset($requestData->Enquiry) && $requestData->Enquiry->state == 5) ? 'CGST:'.number_format($IGST/2,2) : '-' ;
            $row['IGST'] = (isset($requestData->Enquiry) && $requestData->Enquiry->state == 5) ? '-' :  number_format($IGST,2);
            $row['After GST'] = $requestData->amount_paid;
            $row['State'] =  $requestData->state ;
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
     * @param Request $request
     * @return mixed
     * @desc download pdf
     */
    public function downloadpdf(request $request)
    {
        $requestData = $request->all();
        if(!empty($requestData)) {
            if($requestData['type'] == 'online') {
                $id = $requestData['id'];
                $data = (array)$this->saledata->getSaleDataFromId($id);
                if(!empty($data)) {
                    $rateBeforeGst = $data['amount_paid']*100/118;
                    $IGST = $data['amount_paid'] -  $rateBeforeGst;
                    if($data['state_id'] == 5){
                        $cgstSgst = $IGST/2;
                        $data['cgst'] = $data['sgst'] = number_format($cgstSgst,2);
                        $data['igst'] = 0;
                    }else {
                        $data['cgst'] = $data['sgst'] = 0;
                        $data['igst'] = number_format($IGST,2);
                    }
                    $pdf = PDF::loadView('emails.invoice', $data);
                    return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                }
            }elseif ($requestData['type'] == 'offline') {
                $id = $requestData['id'];
                $data = (array)$this->offlinePayment->getOfflinePaymentForPdfDownload($id);
                if(!empty($data)) {
                    //typecasting the variable like above array for pdf download
                   $data['amount_paid'] = $data['rate_after_gst'];
                   $data['created_at '] = $data['payment_date'];
                   $data['invoice_number'] = $data['invoice_no'];
                   $pdf = PDF::loadView('emails.invoice', $data);
                   /* $html = view('emails.invoice')->render();
                    $defaultOptions = PDF::getOptions();
                    $defaultOptions->setDefaultFont('Courier');*/
                    //return PDF::setOptions($defaultOptions)->load($html)->download();
                   return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                }
            }
        }
    }

}
