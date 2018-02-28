<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class Agent extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_agent';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','amount','mobile',
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $agent = Agent::select('tbl_agent.*');
        return $agent->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
       return Agent::select('tbl_agent.*');
    }

    /**
     * Query to get agent total count
     *
     * @param $dbObject
     * @return integer $agentCount
     */
    public static function getAgentCount($dbObject)
    {
        $agentCount = $dbObject->count();
        return $agentCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return $query
     */
    public function scopeGetAgentData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /**
     * scopeGetFilteredData from App/Models/Agent
     * get filterred agents
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
    public function scopeSortAgentData($query, $request)
    {

        return $query->orderBy(config('constant.agentDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Agent addAgent
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addAgent(array $models = [])
    {
        if (isset($models['id'])) {
            $agent = Agent::find($models['id']);
        } else {
            $agent = new Agent;
            $agent->created_at = date('Y-m-d H:i:s');
        }

       

        $agent->name = $models['name'];
        $agent->email = $models['email'];
        $agent->amount = $models['amount'];
        $agent->mobile = $models['mobile'];
        $agent->updated_at = date('Y-m-d H:i:s');
        $agentId = $agent->save();

        if ($agentId) {
            return $agent;
        } else {
            return false;
        }
    }

    /**
     * get Agent By fieldname getAgentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getAgentByField($id, $field_name)
    {
        return Agent::where($field_name, $id)->first();
    }

    /**
     * Delete Agent
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteAgent($id)
    {
        $delete = Agent::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

}
