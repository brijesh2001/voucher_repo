<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class Promo extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_promo_voucher';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'voucher_code', 'status', 'created_by', 'updated_by'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $promo = Promo::select('tbl_promo_voucher.*');
            $promo->where('status','!=',1);
        return $promo->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return Promo::select('tbl_promo_voucher.*')->where('status','!=',1);
    }

    /**
     * Query to get promo total count
     *
     * @param $dbObject
     * @return integer $promoCount
     */
    public static function getPromoCount($dbObject)
    {
        $promoCount = $dbObject->count();
        return $promoCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetPromoData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /**
     * scopeGetFilteredData from App/Models/Promo
     * get filterred promos
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
    public function scopeSortPromoData($query, $request)
    {

        return $query->orderBy(config('constant.promoDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Promo addPromo
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addPromo(array $models = [])
    {
        if (isset($models['id'])) {
            $promo = Promo::find($models['id']);
        } else {
            $promo = new Promo;
            $promo->created_at = date('Y-m-d H:i:s');
            $promo->created_by = Auth::user()->id;
        }

       
        $promo->voucher_code = $models['voucher_code'];
        
        if (isset($models['status'])) {
            $promo->status = $models['status'];
        } else {
            $promo->status = 0;
        }

        $promo->updated_by = Auth::user()->id;
        $promo->updated_at = date('Y-m-d H:i:s');
        $promoId = $promo->save();

        if ($promoId) {
            return $promo;
        } else {
            return false;
        }
    }

    /**
     * get Promo By fieldname getPromoByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getPromoByField($id, $field_name)
    {
        return Promo::where($field_name, $id)->first();
    }

    /**
     * update Promo Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $promo = Promo::find($models['id']);
        $promo->status = $models['status'];
        $promo->updated_at = date('Y-m-d H:i:s');
        $promoId = $promo->save();
        if ($promoId)
            return $promo;
        else
            return false;

    }

    /**
     * Delete Promo
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deletePromo($id)
    {
        $delete = Promo::where('id', $id)->delete();
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
        $return = Promo::where('status', 0)->count();
        return $return;
    }

    /**
     * Get the count of unused voucher
     * @param $count
     * @return $return
     */
    public function getVoucherByCount($count)
    {
        $return = Promo::where('status', 0)->take($count)->get();
        return $return;
    }


}
