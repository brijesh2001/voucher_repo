<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $successData;
    public function __construct($successData)
    {
        //
        $this->successData = $successData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $address = 'info@compassoverseas.com';
        $name = 'PTEVoucherCode.com';
            $to = $this->successData['email'];
            $file = $this->successData['file_path'];
            $view = 'emails.invoice_send';
            $subject = 'PTE Voucher Code GST Invoice';

        return $this->view($view)
            ->to($to)
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->attach($file);
    }
}
