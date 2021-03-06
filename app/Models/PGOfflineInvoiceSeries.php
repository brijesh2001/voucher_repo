<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;



class PGOfflineInvoiceSeries extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_pgoffline_invoice_series';
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number', 'sale_id'
    ];

    /**
     * @return int
     * @desc get the last inserted id
     */
    public function getLastInsertedInvoiceId()
    {
        $lastInsertedId = PGOfflineInvoiceSeries::orderby('id', 'desc')->first();
        if(!empty($lastInsertedId)) {
            $invoiceId = $lastInsertedId->id + 1;
        }else {
            $invoiceId = 1;
        }
        return $invoiceId;
    }

    public function insertInvoiceData($data)
    {
         PGOfflineInvoiceSeries::create([
             'invoice_number' => $data['invoice_number'],
             'sale_id' => $data['sale_id'],
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }
}
