<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{

    public function generateInvoicePdf(Invoice $invoice)
    {

        $invoice->loadMissing('client', 'items', 'user');

        return Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');
    }

    public function savePdf(Invoice $invoice)
    {

        $path = 'invoices/' . $invoice->user_id . '/' . $invoice->number . '.pdf';

        $continutPdf = $this->generateInvoicePdf($invoice)->output();
        Storage::put($path, $continutPdf);

        return $path;
    }
}
