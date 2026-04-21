<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice, public int $daysLeft) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reminder: Factura {$this->invoice->number} este scadentă în {$this->daysLeft} zile",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.invoice-reminder');
    }
}
