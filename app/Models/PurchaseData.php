<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class PurchaseData extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_purchase_data';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_date', 'received_date','invoice_number','quantity','per_voucher_prize','total_amount','rtgs','narration'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $purchasedata = PurchaseData::select('tbl_purchase_data.*');
        return $purchasedata->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return PurchaseData::select('tbl_purchase_data.*');
    }

    /**
     * Query to get purchasedata total count
     *
     * @param $dbObject
     * @return integer $purchasedataCount
     */
    public static function getPurchaseDataCount($dbObject)
    {
        $purchasedataCount = $dbObject->count();
        return $purchasedataCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return $query
     */
    public function scopeGetPurchaseDataData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    public function scopeGetFilteredPurchaseData($query)
    {
        return $query->get();
    }
    /**
     * scopeGetFilteredData from App/Models/PurchaseData
     * get filterred purchasedatas
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
    public function scopeSortPurchaseDataData($query, $request)
    {

        return $query->orderBy(config('constant.purchasedataDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update PurchaseData addPurchaseData
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addPurchaseData(array $models = [])
    {
        if (isset($models['id'])) {
            $purchasedata = PurchaseData::find($models['id']);
        } else {
            $purchasedata = new PurchaseData;
            $purchasedata->created_at = date('Y-m-d H:i:s');
        }

       
        $purchasedata->purchase_date = date("Y-m-d H:i:s", strtotime($models['purchase_date']));
        $purchasedata->received_date = date("Y-m-d H:i:s", strtotime($models['received_date']));
        $purchasedata->invoice_number = $models['invoice_number'];
        $purchasedata->rtgs = $models['rtgs'];
        $purchasedata->narration = $models['narration'];
        $purchasedata->quantity = $models['quantity'];
        $purchasedata->per_voucher_prize = $models['per_voucher_prize'];
        $purchasedata->total_amount = $models['total_amount'];



        $purchasedata->updated_at = date('Y-m-d H:i:s');
        $purchasedataId = $purchasedata->save();

        if ($purchasedataId) {
            return $purchasedata;
        } else {
            return false;
        }
    }

    /**
     * get PurchaseData By fieldname getPurchaseDataByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getPurchaseDataByField($id, $field_name)
    {
        return PurchaseData::where($field_name, $id)->first();
    }

    /**
     * Delete PurchaseData
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deletePurchaseData($id)
    {
        $delete = PurchaseData::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

}
