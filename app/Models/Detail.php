<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class Detail extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_detail';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_title ', 'created_by', 'updated_by','saved_prize'
    ];

    

    /**
     * Add & update Detail addDetail
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addDetail(array $models = [])
    {
        $current_detail = $this->getFirstDetail();
        if(count($current_detail) > 0 ) {
            $detail = Detail::find($current_detail->id);
        }else {
            $detail = new Detail;
            $detail->created_at = date('Y-m-d H:i:s');
            $detail->created_by = Auth::user()->id;
        }

       
        $detail->page_title = $models['page_title'];
        $detail->updated_by = Auth::user()->id;
        $detail->updated_at = date('Y-m-d H:i:s');
        $detailId = $detail->save();

        if ($detailId) {
            return $detail;
        } else {
            return false;
        }
    }

    /**
     * get Detail By fieldname getDetailByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getDetailByField($id, $field_name)
    {
        return Detail::where($field_name, $id)->first();
    }

    /**
     * get Detail By fieldname getDetailByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getFirstDetail()
    {
        return Detail::first();
    }

    /**
     * Add & update Detail addDetail
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addSavedPrize(array $models = [])
    {
        $current_detail = $this->getFirstDetail();
        if(count($current_detail) > 0 ) {
            $detail = Detail::find($current_detail->id);
        }else {
            $detail = new Detail;
            $detail->created_at = date('Y-m-d H:i:s');
            $detail->created_by = Auth::user()->id;
        }


        $detail->saved_prize = $models['saved_prize'];
        $detail->updated_by = Auth::user()->id;
        $detail->updated_at = date('Y-m-d H:i:s');
        $detailId = $detail->save();

        if ($detailId) {
            return $detail;
        } else {
            return false;
        }
    }

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getData()
    {

        $data = Detail::select('tbl_detail.*');
        return $data->first();
    }
}
