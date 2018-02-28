<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Enquiry;
use App\Models\PendingVoucher;
use Carbon\Carbon;
use App\Models\Promo;

class UpdateUnusedVoucher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:unusedvoucher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the unused voucher after inquiry';

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
        $current_time = Carbon::now();
        $compare_time = $current_time->subMinutes(10);

        $promo_count =   DB::table('tbl_promo_voucher')
                        ->select('*')
                        ->where('status', '=', 2)
                        ->where('updated_at', '<=', $compare_time)
                        ->get();
        if(!empty($promo_count)) {
            foreach ($promo_count as $promo) {
                Promo::where('id', $promo->id)
                    ->update(['status' => 0]);
            }
        }
    }
}
