<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class ExpenseData extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_expense_data';
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'name','detail','before_gst','gst','after_gst','gstn','invoice_number','invoice_date','sgst','cgst'
    ];



    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

        $expensedata = ExpenseData::select('tbl_expense_data.*');
        return $expensedata->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return ExpenseData::select('tbl_expense_data.*');
    }

    /**
     * Query to get expensedata total count
     *
     * @param $dbObject
     * @return integer $expensedataCount
     */
    public static function getExpenseDataCount($dbObject)
    {
        $expensedataCount = $dbObject->count();
        return $expensedataCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return $query
     */
    public function scopeGetExpenseDataData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    public function scopeGetFilteredExpenseData($query)
    {
        return $query->get();
    }

    /**
     * scopeGetFilteredData from App/Models/ExpenseData
     * get filterred expensedatas
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

            /*if (count($Datefilter) > 0) {
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
    public function scopeSortExpenseDataData($query, $request)
    {

        return $query->orderBy(config('constant.expensedataDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update ExpenseData addExpenseData
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addExpenseData(array $models = [])
    {
        if (isset($models['id'])) {
            $expensedata = ExpenseData::find($models['id']);
        } else {
            $expensedata = new ExpenseData;
            $expensedata->created_at = date('Y-m-d H:i:s');
        }


        $expensedata->invoice_date = date("Y-m-d H:i:s", strtotime($models['invoice_date']));
        $expensedata->date = date("Y-m-d H:i:s", strtotime($models['date']));
        $expensedata->invoice_number = $models['invoice_number'];
        $expensedata->name = $models['name'];
        $expensedata->detail = $models['detail'];
        $expensedata->gstn = $models['gstn'];
        $expensedata->sgst = $models['sgst'];
        $expensedata->cgst = $models['cgst'];
        $expensedata->hsn_sac = $models['hsn_sac'];
        $expensedata->before_gst = $models['before_gst'];
        $expensedata->gst = $models['gst'];
        $expensedata->after_gst = $models['after_gst'];
        $expensedata->updated_at = date('Y-m-d H:i:s');
        $expensedataId = $expensedata->save();

        if ($expensedataId) {
            return $expensedata;
        } else {
            return false;
        }
    }

    /**
     * get ExpenseData By fieldname getExpenseDataByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getExpenseDataByField($id, $field_name)
    {
        return ExpenseData::where($field_name, $id)->first();
    }

    /**
     * Delete ExpenseData
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteExpenseData($id)
    {
        $delete = ExpenseData::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

}
