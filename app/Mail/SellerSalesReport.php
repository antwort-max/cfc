<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class SellerSalesReport extends Mailable
{
    use Queueable, SerializesModels;

    public $topSellers;
    public $period;

    public function __construct($topSellers, $period)
    {
        $this->topSellers = $topSellers;
        $this->period = $period;
    }

    public function build()
    {
        $pdf = Pdf::loadView('filament.bi.pdf.seller-report', [
            'topSellers' => $this->topSellers,
            'period' => $this->period,
        ]);

        return $this->subject("Informe de Ventas - Últimos {$this->period} días")
            ->markdown('emails.seller-report') // puedes crear este archivo o cambiarlo por `->view(...)`
            ->attachData($pdf->output(), 'informe-vendedores.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
