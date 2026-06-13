<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class InvoiceOverdueMail extends Mailable
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
            subject: 'Factură restantă ' . $this->invoice->number . ' - plată depășită',
        );
    }

    
    public function content()
    {
        return new Content(view: 'emails.invoice-overdue');
    }
}
