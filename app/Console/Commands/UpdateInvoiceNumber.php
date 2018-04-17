<?php

namespace App\Console\Commands;

use App\Models\SaleData;
use Illuminate\Console\Command;
use DB;
use App\Models\OnlineInvoiceSeries;

class UpdateInvoiceNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the invoice number';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * For updating the status of unused voucher
     *
     * @return mixed
     */
    public function handle()
    {

       $promo_count =  DB::select('SELECT * FROM `tbl_sale_data` WHERE `created_at` BETWEEN \'2018-04-01 00:00:00.000000\' AND \'2018-04-17 21:00:00.000000\'');
       $saleData = new SaleData();
        if(!empty($promo_count)) {
            foreach ($promo_count as $promo) {
                $invoice_series = $saleData->generateInvoiceSeries();
                SaleData::where('id', $promo->id)
                    ->update(['invoice_number' => $invoice_series]);
                OnlineInvoiceSeries::create([
                    'invoice_number' => $invoice_series,
                    'sale_id' => $promo->id,
                    'created_at' => $promo->created_at,
                    'updated_at' => $promo->updated_at,
                ]);
            }
        }
    }
}
