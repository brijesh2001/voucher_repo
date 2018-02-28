<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;




class SaleData extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_sale_data';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enquiry_id', 'voucher_id', 'payment_code', 'rate','amount_paid','number_of_voucher','invoice_number','client_gstn'
    ];

    public function Enquiry()
    {
        return $this->hasOne('App\Models\Enquiry', 'id', 'enquiry_id');
    }

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $saledata = SaleData::with('Enquiry');

        return $saledata->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return SaleData::join('tbl_enquiry', 'tbl_enquiry.id', '=', 'tbl_sale_data.enquiry_id')
           ->join('tbl_state','tbl_state.id','=','tbl_enquiry.state')
            ->select('tbl_enquiry.name','tbl_enquiry.email','tbl_enquiry.mobile','tbl_state.name as state', 'tbl_sale_data.*');
    }

    /**
     * Query to get saledata total count
     *
     * @param $dbObject
     * @return integer $saledataCount
     */
    public static function getSaleDataCount($dbObject)
    {
        $saledataCount = $dbObject->count();
        return $saledataCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetSaleDataData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    public function scopeGetFilteredSaleData($query)
    {
        return $query->get();
    }
    /**
     * scopeGetFilteredData from App/Models/SaleData
     * get filterred saledatas
     *
     * @param  object $query
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function scopeGetFilteredData($query, $request)
    {
        $filter = $request->filter;
        $Datefilter = $request->filterDate;
        $Datefilter1 = $request->filterDate1;
        $filterSelect = $request->filterSelect;

        /**
         * @param string $filter  text type value
         * @param string $Datefilter  date type value
         * @param string $filterSelect select value
         *
         * @return mixed
         */
        return $query->Where(function ($query) use ($filter, $Datefilter, $filterSelect,$Datefilter1) {
            if (count($filter) > 0) {
                foreach ($filter as $key => $value) {
                    if ($value != "") {
                        $query->where($key, 'LIKE', '%' . trim($value) . '%');
                    }
                }
            }

           /* if (count($Datefilter) > 0) {
                foreach ($Datefilter as $dtkey => $dtvalue) {
                    if ($dtvalue != "") {
                        $query->where($dtkey, 'LIKE', '%' . date('Y-m-d', strtotime(trim($dtvalue))) . '%');
                    }
                }
            }*/

            if (count($Datefilter) > 0) {
                foreach ($Datefilter as $dtkey => $dtvalue) {
                        foreach ($Datefilter1 as $dtvalue1){
                            if ($dtvalue != "" && $dtvalue1 !="") {
                                $start_date = date('Y-m-d 00:00:00', strtotime(trim($dtvalue)));
                                $end_date = date('Y-m-d 23:59:59', strtotime(trim($dtvalue1)));
                                $query->whereBetween($dtkey,[$start_date,$end_date]);
                            }
                        }
                }
            }

            if (count($filterSelect) > 0) {
                foreach ($filterSelect as $Sekey => $Sevalue) {
                    if ($Sevalue != "") {
                        $query->whereRaw('FIND_IN_SET(' . trim($Sevalue) . ',' . $Sekey . ')');
                    }
                }
            }

        });

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortSaleDataData($query, $request)
    {

        return $query->orderBy(config('constant.saledataDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $column
     * @param  string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortDefaultDataByRaw($query, $column, $dir)
    {
        return $query->orderBy($column, $dir);
    }

    /**
     * Add & update SaleData addSaleData
     *
     * @param array $models
     * @return $saledata
     */
    public function addSaleData(array $models = [])
    {


        //For Generating the Invoice number
        $invoice_number = $this->generateInvoiceNumber();
        $saledata = new SaleData;
        if(isset($models['client_gstn']) && !empty($models['client_gstn'])) {
            $saledata->client_gstn =  $models['client_gstn'];
        }else {
            $saledata->client_gstn = 'NONE';
        }
        $saledata->created_at = date('Y-m-d H:i:s');
        $saledata->invoice_number = $invoice_number;
        $saledata->voucher_id = $models['voucher_id'];
        $saledata->voucher_code = $models['voucher_code'];
        $saledata->instamojo_fee = $models['instamojo_fee'];
        $saledata->enquiry_id = $models['enquiry_id'];
        $saledata->payment_code = $models['payment_code'];
        $saledata->rate = $models['rate'];
        $saledata->amount_paid = $models['amount_paid'];
        $saledata->number_of_voucher = $models['number_of_voucher'];
        $saledata->updated_at = date('Y-m-d H:i:s');
        $saledataId = $saledata->save();

        if ($saledataId) {
            return $saledata;
        } else {
            return false;
        }
    }

    /**
     * get SaleData By fieldname getSaleDataByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getSaleDataByField($id, $field_name)
    {
        return SaleData::where($field_name, $id)->first();
    }

    /**
     * update SaleData Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $saledata = SaleData::find($models['id']);
        $saledata->status = $models['status'];
        $saledata->updated_at = date('Y-m-d H:i:s');
        $saledataId = $saledata->save();
        if ($saledataId)
            return $saledata;
        else
            return false;

    }

    /**
     * Delete SaleData
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteSaleData($id)
    {
        $delete = SaleData::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * Get the count of unused voucher
     *
     * @return $return
     */
    public function getUnusedVoucher()
    {
        $return = SaleData::where('status', 0)->count();
        return $return;
    }

    /**
     * Get the count of unused voucher
     * @param $count
     * @return $return
     */
    public function getVoucherByCount($count)
    {
        $return = SaleData::where('status', 0)->take($count)->get();
        return $return;
    }

    /*
     * Generate the Invoice number
     *
     * */
    public function generateInvoiceNumber()
    {
        $sale_data_count = DB::select('select id from tbl_sale_data ORDER BY id DESC limit 1');
        if($sale_data_count) {
            $last_sale_data = $sale_data_count[0]->id;
            $new_invoice_number = (float)$last_sale_data + 1;
            $current_year = date('Y');
            $invoice_number = 'INV_'.$current_year.'_'.$new_invoice_number;
            return $invoice_number;
        }else {

            $current_year = date('Y');
            $invoice_number = 'INV_'.$current_year.'_1';
            return $invoice_number;
        }
    }

    /**
     * Get the Daily Enquiry Data
     *
     * @return $count
     * */

    public function getDailyEnquiryCount()
    {
        $count = SaleData::whereDate('created_at', '=', date('Y-m-d'))->count();
        return $count;
    }

    /**
     * Get the Daily Enquiry Data
     *
     * @return $count
     */

    public function getLastDayEnquiryCount()
    {
        $start_date_raw =  Carbon::now();
        $start_date = $start_date_raw->toDateTimeString();
        $end_date = date('Y-m-d 23:59:59', strtotime(trim("-1 days")));
        //$result = DB::select("select * from tbl_enquiry WHERE created_at BETWEEN '".$end_date."' and '$start_date' ");
        $count = SaleData::whereBetween('created_at',[$end_date,$start_date])->count();
        return $count;
    }

    /**
     * Get the Daily Enquiry Data
     *
     * @return $count
     */

    public function getLastWeekEnquiryCount()
    {
        $start_date_raw =  Carbon::now();
        $start_date = $start_date_raw->toDateTimeString();
        $end_date = date('Y-m-d', strtotime(trim("-7 days")));
        /*$result = DB::select("select * from tbl_enquiry WHERE created_at BETWEEN '".$end_date."' and '$start_date' ");
        dd($result);*/
        $count = SaleData::whereBetween('created_at',[$end_date,$start_date])->count();
        return $count;
    }

    /**
     * Get the 30 Days Count
     *
     * @return $count
     */

    public function last30DaysCount()
    {
        $start_date_raw =  Carbon::now();
        $start_date = $start_date_raw->toDateTimeString();
        $end_date = date('Y-m-d 23:59:59', strtotime(trim("-30 days")));

        $count = SaleData::whereBetween('created_at',[$end_date,$start_date])->count();
        return $count;
    }

    /**
     * Get all Days Count
     *
     * @return $count
     */

    public function allSaleCount()
    {
        $count = SaleData::select('tbl_sale_data.*')->count();
        return $count;
    }

}
