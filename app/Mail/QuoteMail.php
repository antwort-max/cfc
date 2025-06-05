<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $mailData;

    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

    public function build()
    {
        return $this
            ->subject('Cotización de carrito – ' . now()->format('d-m-Y'))
            // Pasa explícitamente el array a la vista
            ->markdown('emails.quote', $this->mailData);
    }
}