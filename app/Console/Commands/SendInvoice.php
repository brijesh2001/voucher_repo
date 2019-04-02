<?php

namespace App\Console\Commands;

use App\Mail\InvoiceMail;
use App\Models\Enquiry;
use App\Models\SaleData;
use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Mail;
use PDF;
use Storage;

class SendInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice to the customer';

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
       $date = Carbon::yesterday()->format('Y-m-d');
        $start_date = date('Y-m-d 00:00:00', strtotime(trim($date)));
        $end_date = date('Y-m-d 23:59:59', strtotime(trim($date)));
        $saleData = new SaleData();

        $onlineSaleData = $saleData->gettheSaleData($start_date,$end_date);
        if(count($onlineSaleData) > 0) {
            $folder_name = date('Y-m-d', strtotime(trim($date)));
            $filepath = public_path(). DIRECTORY_SEPARATOR.'attachment/'.$folder_name;
            if (!file_exists($filepath)) {
                mkdir($filepath,0777,true);
            }
            foreach ($onlineSaleData as $online) {
                $replaced_file_name = str_replace('/',''-'',$online->invoice_number);
                if(file_exists($filepath.'/'.$replaced_file_name.'.pdf')){
                    $fileFullPath = $filepath.'/'.$replaced_file_name.'.pdf';
                    $this->deleteFilesIfExist($fileFullPath);
                }
                $data['rate_before_gst'] = $online->amount_paid * 100/118;
                $IGST = $online->amount_paid -  $data['rate_before_gst'];
                if($online->state_id == 5){
                    $cgstSgst = $IGST/2;
                    $data['cgst'] = $data['sgst'] = number_format($cgstSgst,2);
                    $data['igst'] = 0;
                }else {
                    $data['cgst'] = $data['sgst'] = 0;
                    $data['igst'] = number_format($IGST,2);
                }
                $data['word_amount'] = $this->getIndianCurrency($online->amount_paid);
                $data['amount_paid'] = $online->amount_paid;
                $data['created_at'] = date("d-m-Y", strtotime($online->created_at));
                $data['name'] = $online->name;
                $data['email'] = $online->email;
                $data['mobile'] = $online->mobile;
                $data['state_name'] = $online->state_name;
                //$data['voucher_code'] = $online->voucher_code;
                $data['voucher_code'] = str_replace(',', '<br />', $online->voucher_code);
                $data['invoice_number'] = $online->invoice_number;
                $data['word_amount'] = $this->getIndianCurrency($online->amount_paid);
                $pdf = PDF::loadView('emails.invoice', $data);

                $pdf->save($filepath.'/'.$replaced_file_name.'.pdf');
                //Storage::put($data['invoice_number'].'.pdf', $pdf->output());
                $filename = $filepath.'/'.$replaced_file_name.'.pdf';
                $customer_email_data = [];
                $customer_email_data['email'] = $online->email;
                $customer_email_data['file_path'] = $filename;
                Mail ::send(new InvoiceMail($customer_email_data));
                sleep(2);

            }


        }
    }


    function getIndianCurrency($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

    /**
     * Check and delete files
     *
     * */

    public function deleteFilesIfExist($filePath)
    {
        return Storage::delete($filePath);
    }
}
