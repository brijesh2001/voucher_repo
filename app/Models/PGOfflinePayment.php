<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Mail;
use DB;




class PGOfflinePayment extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_pgoffline_payment';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name','mobile','state', 'payment_date','invoice_no','gstn','hsn','item','detail','transaction_id',
        'rate_before_gst','rate_after_gst','cgst','sgst','igst','state','payment_type'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $pgofflinePayment = PGOfflinePayment::select('tbl_pgoffline_payment.*');
        return $pgofflinePayment->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       //return PGOfflinePayment::select('tbl_pgoffline_payment.*');
       return PGOfflinePayment::join('tbl_state','tbl_state.id','=','tbl_pgoffline_payment.state')
           ->select('tbl_state.name as state_name', 'tbl_pgoffline_payment.*');
    }

    /**
     * Query to get pgofflinePayment total count
     *
     * @param $dbObject
     * @return integer $pgofflinePaymentCount
     */
    public static function getPGOfflinePaymentCount($dbObject)
    {
        $pgofflinePaymentCount = $dbObject->count();
        return $pgofflinePaymentCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetPGOfflinePaymentData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }
    public function scopeGetFilteredPGOfflinePaymentData($query)
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
    public function scopeSortPGOfflinePaymentData($query, $request)
    {

        return $query->orderBy(config('constant.pgofflinePaymentDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update PGOfflinePayment addPGOfflinePayment
     *
     * @param array $models
     * @return  $pgofflinePaymentId
     */
    public function storeNewAgentPayment(array $models = [])
    {
//
        $gst_invoice_calculation = $this->calculateGstAndInvoiceNumber($models);
        $models = array_merge($gst_invoice_calculation,$models);
        $payment =  PGOfflinePayment::create([
           'payment_date' => date("Y-m-d", strtotime($models['payment_date'])) ,
           'invoice_no' => $models['invoice_no'],
           'name' => $models['name'],
           'email' => $models['email'],
           'mobile' => $models['mobile'],
           'gstn' => $models['gstn'],
           'payment_type' => $models['payment_type'],
           'hsn' => '9992',
           'item' => $models['item'],
           'detail' => $models['detail'],
           'transaction_id' => $models['transaction_id'],
           'rate_before_gst' => round($models['rate_before_gst'],2),
           'rate_after_gst' => round($models['rate_after_gst'],2),
           'cgst' => round($models['cgst'],2),
           'sgst' => round($models['sgst'],2),
           'igst' => round($models['igst'],2),
           'state' => $models['state'],
           'created_at' => date('Y-m-d H:i:s'),
           'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if($payment) {
            $invoice_data = [];
            $invoice_data['invoice_number'] = $payment->invoice_no;
            $invoice_data['sale_id'] = $payment->id;
            $invoiceSeries = new PGOfflineInvoiceSeries();
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
        $returnData['rate_before_gst'] = $data['rate_after_gst']*100/118;
        $IGST = $data['rate_after_gst'] -  $returnData['rate_before_gst'];
        if(!empty($data)){

            //For gst and isgst calculation
            if($data['state'] == 5){
              $returnData['cgst'] = $returnData['sgst'] = $IGST/2;
              $returnData['igst'] = 0;
            }else {
                $returnData['cgst'] = $returnData['sgst'] = 0;
                $returnData['igst'] = $IGST;
            }

            // For invoice number
            $invoiceSeries = new PGOfflineInvoiceSeries();
            if(isset($data['invoice_no'])) {
                $returnData['invoice_no'] = $data['invoice_no'];
            }else {
                $invoice_number = $invoiceSeries->getLastInsertedInvoiceId();
                //$current_year = date('Y');
                $returnData['invoice_no'] = 'INV/1920/PGIND'.$invoice_number;
            }

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
            $agent_data = PGOfflinePayment::find($models['id']);
        } else {
            $agent_data = new PGOfflinePayment();
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
        $agent_data->gstn = $models['gstn'];
        $agent_data->hsn = $models['hsn'];
        $agent_data->payment_type = $models['payment_type'];
        $agent_data->item = $models['item'];
        $agent_data->detail = $models['detail'];
        $agent_data->transaction_id = $models['transaction_id'];
        $agent_data->rate_before_gst = round($models['rate_before_gst'],2);
        $agent_data->rate_after_gst = round($models['rate_after_gst'],2);
        $agent_data->cgst = round($models['cgst'],2);
        $agent_data->sgst = round($models['sgst'],2);
        $agent_data->igst = round($models['igst'],2);
        $agent_data->state = $models['state'];
        $agent_data->updated_at = date('Y-m-d H:i:s');

        $promoId = $agent_data->save();

        if ($promoId) {
            return $agent_data;
        } else {
            return false;
        }
    }

    /**
     * get PGOfflinePayment By fieldname getPGOfflinePaymentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getPGOfflinePaymentByField($id, $field_name)
    {
        return PGOfflinePayment::where($field_name, $id)->first();
    }


    /**
     * Delete PGOfflinePayment
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteAgent($id)
    {
        $delete = PGOfflinePayment::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * @param $id
     * @return array
     *
     * @desc get pdf download for pgoffline payment
     */
    public function getPGOfflinePaymentForPdfDownload($id)
    {
        $result =  DB::select('select tbo.*, ss.name as state_name,ss.id as state_id,ss.code as code 
                            from tbl_pgoffline_payment tbo
                            LEFT JOIN tbl_state ss ON ss.id = tbo.state
                            where tbo.id = :id',
            ['id' =>$id]);
        return (!empty($result)) ? $result[0]: [];
    }


    /**
     * Get the PGOffline agent payment data
     *
     * */
    public function  getUniquePGOfflineAgent()
    {
        $result = DB::select('SELECT DISTINCT rate_after_gst,name,id,email FROM tbl_pgoffline_payment ORDER BY id');
        return $result;
    }

    /**
     * get all agent data by payment
     * */

    public function getAllAgent($term)
    {
        return PGOfflinePayment::where('name','LIKE','%'.$term.'%')->get();
    }

    /**
     * Get the pgoffline data by date range
     * */
    public function getthePGOfflineData($start_date,$end_date)
    {
       return PGOfflinePayment::whereBetween('payment_date', [$start_date, $end_date])
                                ->leftjoin('tbl_state','tbl_state.id','=','tbl_pgoffline_payment.state')
                                ->select('tbl_state.name as state_name','tbl_state.code as codefooter', 'tbl_pgoffline_payment.*')->get();
    }
}
