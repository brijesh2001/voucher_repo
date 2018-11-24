<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Mail;
use DB;




class OverseasPayment extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_overseas_payment';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name','mobile', 'payment_date','invoice_no','lut','item','detail','transaction_id',
        'amount','payment_type'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $overseasPayment = OverseasPayment::select('tbl_overseas_payment.*');
        return $overseasPayment->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       //return OverseasPayment::select('tbl_overseas_payment.*');
       return OverseasPayment::select('tbl_overseas_payment.*');
    }

    /**
     * Query to get overseasPayment total count
     *
     * @param $dbObject
     * @return integer $overseasPaymentCount
     */
    public static function getOverseasPaymentCount($dbObject)
    {
        $overseasPaymentCount = $dbObject->count();
        return $overseasPaymentCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetOverseasPaymentData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }
    public function scopeGetFilteredOverseasPaymentData($query)
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
    public function scopeSortOverseasPaymentData($query, $request)
    {

        return $query->orderBy(config('constant.overseasPaymentDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update OverseasPayment addOverseasPayment
     *
     * @param array $models
     * @return  $overseasPaymentId
     */
    public function storeNewAgentPayment(array $models = [])
    {
//
        $gst_invoice_calculation = $this->calculateGstAndInvoiceNumber($models);
        $models = array_merge($gst_invoice_calculation,$models);
        $payment =  OverseasPayment::create([
           'payment_date' => date("Y-m-d", strtotime($models['payment_date'])) ,
           'invoice_no' => $models['invoice_no'],
           'name' => $models['name'],
           'email' => $models['email'],
           'mobile' => $models['mobile'],
           'lut' => $models['lut'],
           'payment_type' => $models['payment_type'],
           'item' => $models['item'],
           'detail' => $models['detail'],
           'transaction_id' => $models['transaction_id'],
           'amount' => round($models['amount'],2),
           'created_at' => date('Y-m-d H:i:s'),
           'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if($payment) {
            $invoice_data = [];
            $invoice_data['invoice_number'] = $payment->invoice_no;
            $invoice_data['sale_id'] = $payment->id;
            $invoiceSeries = new OverseasInvoiceSeries();
            $invoiceSeries->insertInvoiceData($invoice_data);
        }
        return $payment;
    }

    /**
     * @param $data
     * @return array
     * @desc calculate the gst and invoice number
     */
    public function calculateGstAndInvoiceNumber($data)
    {
        $returnData = [];
        // For invoice number
        $invoiceSeries = new OverseasInvoiceSeries();
        if(isset($data['invoice_no'])) {
            $returnData['invoice_no'] = $data['invoice_no'];
        }else {
            $invoice_number = $invoiceSeries->getLastInsertedInvoiceId();
            $current_year = date('Y');
            $returnData['invoice_no'] = $current_year.'-PGEXP-'.$invoice_number;
        }
        return $returnData;
    }


    /**
     * Add & update Promo addPromo
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateAgent(array $models = [])
    {
        if (isset($models['id'])) {
            $agent_data = OverseasPayment::find($models['id']);
        } else {
            $agent_data = new OverseasPayment();
            $agent_data->created_at = date('Y-m-d H:i:s');
            $agent_data->created_by = Auth::user()->id;

        }

        $gst_invoice_calculation = $this->calculateGstAndInvoiceNumber($models);
        $models = array_merge($gst_invoice_calculation,$models);
        $agent_data->payment_date = date("Y-m-d", strtotime($models['payment_date']));
        $agent_data->invoice_no = $models['invoice_no'];
        $agent_data->name = $models['name'];
        $agent_data->email = $models['email'];
        $agent_data->mobile = $models['mobile'];
        $agent_data->lut = $models['lut'];
        $agent_data->payment_type = $models['payment_type'];
        $agent_data->item = $models['item'];
        $agent_data->detail = $models['detail'];
        $agent_data->transaction_id = $models['transaction_id'];
        $agent_data->amount = round($models['amount'],2);
        $agent_data->updated_at = date('Y-m-d H:i:s');
        $promoId = $agent_data->save();

        if ($promoId) {
            return $agent_data;
        } else {
            return false;
        }
    }

    /**
     * get OverseasPayment By fieldname getOverseasPaymentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getOverseasPaymentByField($id, $field_name)
    {
        return OverseasPayment::where($field_name, $id)->first();
    }


    /**
     * Delete OverseasPayment
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteAgent($id)
    {
        $delete = OverseasPayment::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * @param $id
     * @return array
     *
     * @desc get pdf download for overseas payment
     */
    public function getOverseasPaymentForPdfDownload($id)
    {
        $result =  DB::select('select * from tbl_overseas_payment where id = :id',['id' =>$id]);
        return (!empty($result)) ? $result[0]: [];
    }


    /**
     * get all agent data by payment
     * */

    public function getAllAgent($term)
    {
        return OverseasPayment::where('name','LIKE','%'.$term.'%')->get();
    }

    /**
     * Get the overseas data by date range
     * */
    public function gettheOverseasData($start_date,$end_date)
    {
       return OverseasPayment::whereBetween('payment_date', [$start_date, $end_date])
                                ->select('tbl_overseas_payment.*')->get();
    }
}
