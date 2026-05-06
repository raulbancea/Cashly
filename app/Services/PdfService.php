<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateInvoicePdf(Invoice $invoice): \Barryvdh\DomPDF\PDF
    {
        $invoice->loadMissing('client', 'items', 'user');

        return Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');
    }

    public function savePdf(Invoice $invoice): string
    {
        $path = 'invoices/' . $invoice->user_id . '/' . $invoice->number . '.pdf';
        Storage::put($path, $this->generateInvoicePdf($invoice)->output());
        return $path;
    }
}
