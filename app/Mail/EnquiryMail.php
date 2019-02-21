<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $enquiryData;
    public $user_name;
    public $type;
    public $state;
    public function __construct($enquiryData,$type,$state)
    {
        //
        $this->enquiryData = $enquiryData;
        $this->type = $type;
        $this->state = $state;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if($this->type == 'customer') {
            $to = $this->enquiryData->email;
            $address = 'info@ptevouchercode.com';
            $name = 'PTEVoucherCode.com';
            $subject = 'Thank you';
            $view = 'emails.enquiry';
        }elseif ($this->type == 'admin') {
            $address = 'info@ptevouchercode.com';
            $name = 'PTEVoucherCode.com';
            $subject = $this->enquiryData->name . 'Inquiry';
            $to = 'info@ptevouchercode.com';
            $view = 'emails.admin_enquiry';
        }

        return $this->view($view)
            ->to($to)
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);

    }
}
