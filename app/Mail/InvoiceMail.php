<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Factură ' . $this->invoice->number . ' de la Cashly',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    public function attachments()
    {

        $pdfService = app(PdfService::class);
        $pdf        = $pdfService->generateInvoicePdf($this->invoice);
        $pdfContent = $pdf->output();

        $numeFisier = 'Factura-' . $this->invoice->number . '.pdf';

        return [
            Attachment::fromData(function () use ($pdfContent) {
                return $pdfContent;
            }, $numeFisier)->withMime('application/pdf'),
        ];
    }
}
