<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductAnalysisReportPdf extends Mailable
{
    use Queueable, SerializesModels;

    public string $pdfContent;
    public array  $data;

    public function __construct(string $pdfContent, array $data)
    {
        $this->pdfContent = $pdfContent;
        $this->data       = $data;
    }

    public function build()
    {
        return $this
            ->subject('Análisis de Stock productos más vendidos')
            // Cambiamos a markdown() y pasamos $data a la vista:
            ->markdown('emails.reports.product-analysis', [
                'data' => $this->data,
            ])
            ->attachData(
                $this->pdfContent,
                'product-analysis-report.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
