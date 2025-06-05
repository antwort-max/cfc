<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyReportPdf extends Mailable
{
    use Queueable, SerializesModels;

    public string $pdfContent;
    public array $data;

    /**
     * Create a new message instance.
     *
     * @param  string  $pdfContent
     * @param  array   $data
     * @return void
     */
    public function __construct(string $pdfContent, array $data)
    {
        $this->pdfContent = $pdfContent;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Daily Overview Report')
            ->markdown('emails.reports.today-report', [
                'data' => $this->data,
            ])
            ->attachData(
                $this->pdfContent,
                'daily-overview-report.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
