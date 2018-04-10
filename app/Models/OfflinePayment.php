<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Mail;
use App\Mail\SuccessMail;




class OfflinePayment extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_offline_payment';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name','mobile','state', 'payment_date','invoice_no','gstn','hsn','voucher_code','number_of_voucher','transaction_id',
        'rate_before_gst','rate_after_gst','cgst','sgst','igst','state'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $offlinePayment = OfflinePayment::select('tbl_offline_payment.*');
        return $offlinePayment->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       //return OfflinePayment::select('tbl_offline_payment.*');
       return OfflinePayment::join('tbl_state','tbl_state.id','=','tbl_offline_payment.state')
           ->select('tbl_state.name as state_name', 'tbl_offline_payment.*');
    }

    /**
     * Query to get offlinePayment total count
     *
     * @param $dbObject
     * @return integer $offlinePaymentCount
     */
    public static function getOfflinePaymentCount($dbObject)
    {
        $offlinePaymentCount = $dbObject->count();
        return $offlinePaymentCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetOfflinePaymentData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /**
     * scopeGetFilteredData from App/Models/OfflinePayment
     * get filterred offlinePayments
     *
     * @param  object $query
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function scopeGetFilteredData($query, $request)
    {
        $filter = $request->filter;
        $Datefilter = $request->filterDate;
        $filterSelect = $request->filterSelect;

        /**
         * @param string $filter  text type value
         * @param string $Datefilter  date type value
         * @param string $filterSelect select value
         *
         * @return mixed
         */
        return $query->Where(function ($query) use ($filter, $Datefilter, $filterSelect) {
            if (count($filter) > 0) {
                foreach ($filter as $key => $value) {
                    if ($value != "") {
                        $query->where($key, 'LIKE', '%' . trim($value) . '%');
                    }
                }
            }

            if (count($Datefilter) > 0) {
                foreach ($Datefilter as $dtkey => $dtvalue) {
                    if ($dtvalue != "") {
                        $query->where($dtkey, 'LIKE', '%' . date('Y-m-d', strtotime(trim($dtvalue))) . '%');
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
    public function scopeSortOfflinePaymentData($query, $request)
    {

        return $query->orderBy(config('constant.offlinePaymentDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update OfflinePayment addOfflinePayment
     *
     * @param array $models
     * @return  $offlinePaymentId
     */
    public function storeNewAgentPayment(array $models = [])
    {
//
        $gst_invoice_calculation = $this->calculateGstAndInvoiceNumber($models);
        $models = array_merge($gst_invoice_calculation,$models);
        $payment =  OfflinePayment::create([
           'payment_date' => date("Y-m-d", strtotime($models['payment_date'])) ,
           'invoice_no' => $models['invoice_no'],
           'name' => $models['name'],
           'email' => $models['email'],
           'mobile' => $models['mobile'],
           'gstn' => $models['gstn'],
           'hsn' => 'HSN',
           'voucher_code' => $models['voucher_code'],
           'number_of_voucher' => $models['number_of_voucher'],
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
            $invoiceSeries = new OfflineInvoiceSeries();
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
            $invoiceSeries = new OfflineInvoiceSeries();
            if(isset($data['invoice_no'])) {
                $returnData['invoice_no'] = $data['invoice_no']   ;
            }else {
                $invoice_number = $invoiceSeries->getLastInsertedInvoiceId();
                $returnData['invoice_no'] = 'INV_OFFLINE_'.$invoice_number;
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
            $agent_data = OfflinePayment::find($models['id']);
        } else {
            $agent_data = new OfflinePayment();
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
        $agent_data->voucher_code = $models['voucher_code'];
        $agent_data->number_of_voucher = $models['number_of_voucher'];
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
     * get OfflinePayment By fieldname getOfflinePaymentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getOfflinePaymentByField($id, $field_name)
    {
        return OfflinePayment::where($field_name, $id)->first();
    }


    /**
     * Delete OfflinePayment
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteAgent($id)
    {
        $delete = OfflinePayment::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }




}
