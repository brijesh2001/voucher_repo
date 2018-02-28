<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use App\Mail\EnquiryMail;
use Mail;
use Carbon\Carbon;
use DB;



class Enquiry extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_enquiry';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name','mobile','number_of_voucher','rate','payment_request_id', 'created_by', 'updated_by'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $enquiry = Enquiry::select('tbl_enquiry.*');
        return $enquiry->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return Enquiry::select('tbl_enquiry.*');
    }

    /**
     * Query to get enquiry total count
     *
     * @param $dbObject
     * @return integer $enquiryCount
     */
    public static function getEnquiryCount($dbObject)
    {
        $enquiryCount = $dbObject->count();
        return $enquiryCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetEnquiryData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    public function scopeGetFilteredEnquiryData($query)
    {
        return $query->get();
    }

    /**
     * scopeGetFilteredData from App/Models/Enquiry
     * get filterred enquirys
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
    public function scopeSortEnquiryData($query, $request)
    {

        return $query->orderBy(config('constant.enquiryDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Enquiry addEnquiry
     *
     * @param array $models
     * @return $enquiry
     */
    public function addEnquiry(array $models = [])
    {

        $enquiry = new Enquiry;
        if(isset($models['payment_request_id']) && !empty($models['payment_request_id'])) {
            $payment_request_id = $models['payment_request_id'];
        }else {
            $payment_request_id = 'OFFLINE';
        }
        $enquiry->email = $models['email'];
        $enquiry->name = $models['name'];
        $enquiry->mobile = $models['mobile'];
        $enquiry->number_of_voucher = $models['number_of_voucher'];
        $enquiry->rate = $models['rate'];
        $enquiry->state = $models['state'];
        $enquiry->payment_request_id = $payment_request_id;
        $enquiry->created_at = date('Y-m-d H:i:s');
        $enquiry->created_by = 1;


        $enquiry->updated_by = 1;
        $enquiry->updated_at = date('Y-m-d H:i:s');
        $enquiryId = $enquiry->save();
        if ($enquiryId) {
            Mail::send(new EnquiryMail($enquiry,'customer'));
            sleep(5);
            Mail::send(new EnquiryMail($enquiry,'admin'));

            return $enquiry;
        } else {
            return false;
        }
    }

    /**
     * get Enquiry By fieldname getEnquiryByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getEnquiryByField($id, $field_name)
    {
        return Enquiry::where($field_name, $id)->first();
    }



    /**
     * Delete Enquiry
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteEnquiry($id)
    {
        $delete = Enquiry::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * Get the Daily Enquiry Data
     *
     * @return $count
     * */

    public function getDailyEnquiryCount()
    {
        $count = Enquiry::whereDate('created_at', '=', date('Y-m-d'))->count();
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
        $count = Enquiry::whereBetween('created_at',[$end_date,$start_date])->count();
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
        $count = Enquiry::whereBetween('created_at',[$end_date,$start_date])->count();
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
        $count = Enquiry::whereBetween('created_at',[$end_date,$start_date])->count();
        return $count;
    }

    /**
     * Get the 30 Days Count
     *
     * @return $count
     */

    public function allEnquiryCount()
    {
        $count = Enquiry::select('tbl_enquiry.*')->count();
        return $count;
    }


}
