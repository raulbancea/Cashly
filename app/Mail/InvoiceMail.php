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

    public function __construct(public Invoice $invoice) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Factură {$this->invoice->number} de la Cashly",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    public function attachments(): array
    {
        $pdfService = app(PdfService::class);
        $pdf = $pdfService->generateInvoicePdf($this->invoice);
        $pdfContent = $pdf->output();

        return [
            Attachment::fromData(fn () => $pdfContent, "Factura-{$this->invoice->number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
