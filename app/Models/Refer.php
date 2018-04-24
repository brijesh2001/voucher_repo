<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Mail;
use DB;



class Refer extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_refer';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $refer = Refer::select('tbl_refer.*');
        return $refer->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return Refer::select('tbl_refer.*');
    }

    /**
     * Query to get refer total count
     *
     * @param $dbObject
     * @return integer $referCount
     */
    public static function getReferCount($dbObject)
    {
        $referCount = $dbObject->count();
        return $referCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetReferData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    public function scopeGetFilteredReferData($query)
    {
        return $query->get();
    }

    /**
     * scopeGetFilteredData from App/Models/Refer
     * get filterred refers
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
    public function scopeSortReferData($query, $request)
    {

        return $query->orderBy(config('constant.referDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Refer addRefer
     *
     * @param array $models
     * @return $refer
     */
    public function addRefer(array $models = [])
    {
        $refer = new Refer;
        $refer->email = $models['email'];
        $refer->created_at = date('Y-m-d H:i:s');
        $refer->updated_at = date('Y-m-d H:i:s');
        $referId = $refer->save();
        if ($referId) {
            return $refer;
        } else {
            return false;
        }
    }

    /**
     * get Refer By fieldname getReferByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getReferByField($id, $field_name)
    {
        return Refer::where($field_name, $id)->first();
    }

    /**
     * Delete Refer
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteRefer($id)
    {
        $delete = Refer::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

}
