<?php

namespace App\Http\Controllers;

use App\Models\OfflinePayment;
use App\Models\PGOfflinePayment;
use Illuminate\Http\Request;
use App\Models\SaleData;
use App\Models\Role;
use Validator;
use Event;
use Hash;
use DB;
use Excel;
use PDF;
use Storage;
use Zipper;
use File;
class SaleDataController extends Controller
{

    protected $saledata;
    protected $role;
    protected $offlinePayment;
    protected $pgofflinePayment;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SaleData $saledata, Role $role,OfflinePayment $offlinePayment,PGOfflinePayment $pgofflinePayment)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->saledata = $saledata;
        $this->role = $role;
        $this->offlinePayment = $offlinePayment;
        $this->pgofflinePayment = $pgofflinePayment;
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
            $row[] = '9992';
            $row[] = $saledataData->voucher_code;
            $row[] = $saledataData->number_of_voucher;
            $row[] = $saledataData->payment_code;
            $row[] = number_format(($amount_paid*100)/118,2);
            $row[] = (isset($saledataData->Enquiry) && $saledataData->Enquiry->state == 5) ? number_format($IGST/2,2) : '-' ;
            $row[] = (isset($saledataData->Enquiry) && $saledataData->Enquiry->state == 5) ? number_format($IGST/2,2) : '-' ;
            $row[] = (isset($saledataData->Enquiry) && $saledataData->Enquiry->state == 5) ? '-' : number_format($IGST,2);
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
            $row['HSN/SAC'] = '9992';
            $row['Voucher'] = $requestData->voucher_code;
            $row['Number Of Voucher'] = $requestData->number_of_voucher;
            $row['Transaction Id'] = $requestData->payment_code;
            $row['Before GST'] =  number_format(($amount_paid*100)/118,2);
            $row['SGST'] = (isset($requestData->Enquiry) && $requestData->Enquiry->state == 5) ? number_format($IGST/2,2): '' ;
            $row['CGST'] = (isset($requestData->Enquiry) && $requestData->Enquiry->state == 5) ? number_format($IGST/2,2) : '' ;
            $row['IGST'] = (isset($requestData->Enquiry) && $requestData->Enquiry->state == 5) ? '' :  number_format($IGST,2);
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
                    $data['rate_before_gst'] = $data['amount_paid']*100/118;
                    $data['gstn'] = $data['client_gstn'];
                    $IGST = $data['amount_paid'] -  $data['rate_before_gst'];
                    if($data['state_id'] == 5){
                        $cgstSgst = $IGST/2;
                        $data['cgst'] = $data['sgst'] = number_format($cgstSgst,2);
                        $data['igst'] = 0;
                    }else {
                        $data['cgst'] = $data['sgst'] = 0;
                        $data['igst'] = number_format($IGST,2);
                    }
                    $data['voucher_code'] = str_replace(',', '<br />', $data['voucher_code']);
                    $data['word_amount'] = $this->getIndianCurrency($data['amount_paid']);
                    $data['created_at'] = date("d-m-Y", strtotime($data['created_at']));
 
                    $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                    if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                        $pdf = PDF::loadView('emails.invoice', $data);
                    }else{
                        $pdf = PDF::loadView('emails.new_invoice', $data);
                    }

                    //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                    return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                }
            }elseif ($requestData['type'] == 'offline') {
                $id = $requestData['id'];
                $data = (array)$this->offlinePayment->getOfflinePaymentForPdfDownload($id);
                if(!empty($data)) {
                    //typecasting the variable like above array for pdf download
                   $data['amount_paid'] = $data['rate_after_gst'];
                   $data['created_at'] = date("d-m-Y", strtotime($data['payment_date']));
                   //$data['created_at '] = $data['payment_date'];
                   $data['invoice_number'] = $data['invoice_no'];
                   $data['word_amount'] = $this->getIndianCurrency($data['rate_after_gst']);
                    $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                    if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                        $pdf = PDF::loadView('emails.invoice', $data);
                    }else{
                        $pdf = PDF::loadView('emails.new_invoice', $data);
                    } //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                   return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                }
            }

            elseif ($requestData['type'] == 'pgoffline') {
                $id = $requestData['id'];
                $data = (array)$this->pgofflinePayment->getPGOfflinePaymentForPdfDownload($id);
                if(!empty($data)) {
                    //typecasting the variable like above array for pdf download
                    $data['amount_paid'] = $data['rate_after_gst'];
                    $data['voucher_code'] = $data['item'];
                    $data['number_of_voucher'] = $data['detail'];
                    $data['created_at'] = date("d-m-Y", strtotime($data['payment_date']));
                    //$data['created_at '] = $data['payment_date'];
                    $data['invoice_number'] = $data['invoice_no'];
                    $data['word_amount'] = $this->getIndianCurrency($data['rate_after_gst']);
                    $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                    if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                        $pdf = PDF::loadView('emails.invoice', $data);
                    }else{
                        $pdf = PDF::loadView('emails.new_invoice', $data);
                    }
                    //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                    return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                }
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
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise Only' : '';
        return ($Rupees ? $Rupees . 'Rupees Only ' : '') . $paise;
    }

    /**
     * For Creating the zip file for the online customer
     * */

    public function createZipOfOnlineCustomer()
    {

        $data['saledataManagementTab'] = "active open";
        $data['onlineZipTab'] = "active";
        return view('saledata.onlinezip', $data);
    }

    /**
     * For Creating the zip file for the Offline customer
     * */

    public function createZipOfOfflineCustomer()
    {

        $data['saledataManagementTab'] = "active open";
        $data['offlineZipTab'] = "active";
        return view('saledata.offlinezip', $data);
    }

    /**
     * For Generating the Zip file
     * */
    public function generateZip(request $request)
    {
        $requestData = $request->all();
        if(!empty($requestData)) {
            $start_date = date('Y-m-d 00:00:00', strtotime(trim($requestData['to'])));
            $end_date = date('Y-m-d 23:59:59', strtotime(trim($requestData['from'])));

            //For Offline Data
            if($requestData['type'] == 'offline') {

                $folder_name = date('Y-m-d', strtotime(trim($requestData['to'])));
                $filepath = public_path(). DIRECTORY_SEPARATOR.'agent/'.$folder_name;
                $offlineinvoiceData = $this->offlinePayment->gettheOfflineData($start_date,$end_date);
                if (!file_exists($filepath)) {
                    mkdir($filepath,0777,true);
                }

                if(!empty($offlineinvoiceData)) {
                    foreach ($offlineinvoiceData as $offlineinvoic) {
                        $replaced_file_name = str_replace('/','-',$offlineinvoic->invoice_no);
                        if(file_exists($filepath.'/'.$replaced_file_name.'.pdf')){
                            $fileFullPath = $filepath.'/'.$replaced_file_name.'.pdf';
                            $this->deleteFilesIfExist($fileFullPath);
                        }
                        $data['amount_paid'] = $offlineinvoic->rate_after_gst;
                        $data['name'] = $offlineinvoic->name;
                        $data['email'] = $offlineinvoic->email;
                        $data['mobile'] = $offlineinvoic->mobile;
                        $data['state_name'] = $offlineinvoic->state_name;
                        //$data['voucher_code'] = $offlineinvoic->voucher_code;
                        $data['voucher_code'] = str_replace(',', '<br />', $offlineinvoic->voucher_code);
                        $data['created_at'] = date("d-m-Y", strtotime($offlineinvoic->payment_date));
                        $data['igst'] = $offlineinvoic->igst;
                        $data['cgst'] = $offlineinvoic->cgst;
                        $data['sgst'] = $offlineinvoic->sgst;
                        $data['amount_paid'] = $offlineinvoic->amount_paid;
                        $data['gstn'] = $offlineinvoic->gstn;
                        $data['invoice_number'] = $offlineinvoic->invoice_no;
                        $data['rate_before_gst'] = $offlineinvoic->rate_before_gst;
                        $data['state_name'] = $offlineinvoic->state_name;
                        $data['word_amount'] = $this->getIndianCurrency($offlineinvoic->rate_after_gst);
                        $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                        if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                            $pdf = PDF::loadView('emails.invoice', $data);
                        }else{
                            $pdf = PDF::loadView('emails.new_invoice', $data);
                        }
                        $pdf->save($filepath.'/'.$replaced_file_name.'.pdf');
                        //return $pdf->setPaper('a4')->download($data['invoice_number'].'.pdf');
                    }

                    $files = glob(public_path('agent'.DIRECTORY_SEPARATOR.$folder_name.'/*'));
                    $zipfileName = public_path() . DIRECTORY_SEPARATOR.$folder_name.DIRECTORY_SEPARATOR.'agent'.DIRECTORY_SEPARATOR.$folder_name.'.zip';
                    if(file_exists($zipfileName)){
                        $this->deleteFilesIfExist($zipfileName);
                    }
                    if(count($files) > 0) {
                        Zipper::make('agent/'.$folder_name.'/'.$folder_name.'.zip')->add($files)->close();
                        return response()->download(public_path('agent/'.$folder_name.'/'.$folder_name.'.zip'));
                    }else {
                        $request->session()->flash('alert-danger', 'No files found');
                        return redirect('saledata/create-offline-zip');
                    }

                }

            }

            //PDF Generation for online sale data

            if($requestData['type'] == 'online') {

                $folder_name = date('Y-m-d', strtotime(trim($requestData['to'])));
                $filepath = public_path(). DIRECTORY_SEPARATOR.'online/'.$folder_name;
                if (!file_exists($filepath)) {
                    mkdir($filepath,0777,true);
                    //File::makeDirectory($filepath, 0777,true);
                }
                $onlineSaleData = $this->saledata->gettheSaleData($start_date,$end_date);
                if(!empty($onlineSaleData)) {
                    foreach ($onlineSaleData as $online) {
                        $replaced_file_name = str_replace('/','-',$online->invoice_number);
                        if(file_exists($filepath.'/'.$replaced_file_name.'.pdf')){
                            $fileFullPath = $filepath.'/'.$replaced_file_name.'.pdf';
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
                        $data['gstn'] = $online->client_gstn;
                        //$data['voucher_code'] = $online->voucher_code;

                        $data['voucher_code'] = str_replace(',', '<br />', $online->voucher_code);
                        $data['invoice_number'] = $online->invoice_number;
                        $data['word_amount'] = $this->getIndianCurrency($online->amount_paid);
                        $check_date_for_older_invoice = date('Y-m-d 00:00:00', strtotime(trim('14-05-2019')));
                        if(date('Y-m-d 00:00:00', strtotime(trim($data['created_at']))) < $check_date_for_older_invoice){
                            $pdf = PDF::loadView('emails.invoice', $data);
                        }else{
                            $pdf = PDF::loadView('emails.new_invoice', $data);
                        }
                        $pdf->save($filepath.'/'.$replaced_file_name.'.pdf');
                        //Storage::put($data['invoice_number'].'.pdf', $pdf->output());

                    }

                    $files = glob(public_path('online'.DIRECTORY_SEPARATOR.$folder_name.'/*'));
                    $zipfileName = public_path() . DIRECTORY_SEPARATOR.$folder_name.DIRECTORY_SEPARATOR.'online'.DIRECTORY_SEPARATOR.$folder_name.'.zip';
                    if(file_exists($zipfileName)){
                        $this->deleteFilesIfExist($zipfileName);
                    }
                    if(count($files) > 0) {
                        Zipper::make('online/'.$folder_name.'/'.$folder_name.'.zip')->add($files)->close();
                        return response()->download(public_path('online/'.$folder_name.'/'.$folder_name.'.zip'));
                    }else {
                        $request->session()->flash('alert-danger', 'No files found');
                        return redirect('saledata/create-online-zip');
                    }


                }
            }
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
}
