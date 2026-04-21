<?php

namespace App\Console\Commands;

use App\Mail\InvoiceOverdueMail;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;

class CheckOverdueInvoices extends Command
{
    protected $signature = 'invoices:check-overdue';
    protected $description = 'Marchează facturile trimise și expirate ca restante și trimite email clientului';

    public function handle(): int
    {
        try {
            $invoices = Invoice::withoutGlobalScope('user')
                ->with('client')
                ->where('status', 'sent')
                ->whereDate('due_date', '<', today())
                ->get();

            foreach ($invoices as $invoice) {
                $invoice->update(['status' => 'overdue']);

                if ($invoice->client?->email) {
                    Mail::to($invoice->client->email)->send(new InvoiceOverdueMail($invoice));
                }
            }

            $count = $invoices->count();
            Log::info("invoices:check-overdue: {$count} " . ($count === 1 ? 'factură marcată' : 'facturi marcate') . ' ca restante.');
            $this->info("{$count} " . ($count === 1 ? 'factură marcată' : 'facturi marcate') . ' ca restante.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('invoices:check-overdue a eșuat: ' . $e->getMessage(), ['exception' => $e]);
            $this->error('Eroare la procesare: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
