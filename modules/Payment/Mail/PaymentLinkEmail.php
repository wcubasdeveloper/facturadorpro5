<?php

namespace Modules\Payment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentLinkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $user_payment_link;

    public function __construct($company, $user_payment_link)
    {
        $this->company = $company;
        $this->user_payment_link = $user_payment_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Envio de link de pago')
                    ->from(config('mail.username'), 'Link de pago')
                    ->view('payment::payment_links.emails.public_link');
    }
}