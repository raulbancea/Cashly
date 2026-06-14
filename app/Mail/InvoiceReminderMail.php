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

    public $invoice;

    public $daysLeft;

    public function __construct(Invoice $invoice, $daysLeft)
    {
        $this->invoice  = $invoice;
        $this->daysLeft = $daysLeft;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Reminder: Factura ' . $this->invoice->number . ' este scadentă în ' . $this->daysLeft . ' zile',
        );
    }

    public function content()
    {
        return new Content(view: 'emails.invoice-reminder');
    }
}
