<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $pdfContent;

    public function __construct($pdfContent)
    {
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject('Your Order Report')
            ->html('<p>Thank you for your order! Please find the order report attached.</p>')
            ->attachData($this->pdfContent, 'order_report.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}