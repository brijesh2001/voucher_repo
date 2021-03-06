<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;




class State extends Authenticatable
{
    use Notifiable;
   
    protected $table = 'tbl_state';
    protected $primaryKey = 'id';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    

    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

         $State = State::select('tbl_state.*');
        return $State->get();
    }

    /**
     * get Enquiry By fieldname getEnquiryByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getStateByField($id, $field_name)
    {
        return State::where($field_name, $id)->first();
    }

}
