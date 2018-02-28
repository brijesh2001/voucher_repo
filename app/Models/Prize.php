<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class Prize extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_voucher_prize';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rate', 'created_by', 'updated_by'
    ];

    

    /**
     * Add & update Prize addPrize
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addPrize(array $models = [])
    {
        $current_prize = $this->getFirstPrize();
        if(count($current_prize) > 0 ) {
            $prize = Prize::find($current_prize->id);
        }else {
            $prize = new Prize;
            $prize->created_at = date('Y-m-d H:i:s');
            $prize->created_by = Auth::user()->id;
        }

       
        $prize->rate = $models['rate'];
        $prize->updated_by = Auth::user()->id;
        $prize->updated_at = date('Y-m-d H:i:s');
        $prizeId = $prize->save();

        if ($prizeId) {
            return $prize;
        } else {
            return false;
        }
    }

    /**
     * get Prize By fieldname getPrizeByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getPrizeByField($id, $field_name)
    {
        return Prize::where($field_name, $id)->first();
    }

    /**
     * get Prize By fieldname getPrizeByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getFirstPrize()
    {
        return Prize::first();
    }

}
