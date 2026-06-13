<?php

namespace App\Console\Commands;

use App\Mail\InvoiceOverdueMail;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class CheckOverdueInvoices extends Command
{
    
    protected $signature = 'invoices:check-overdue';

    
    protected $description = 'Marchează facturile trimise și expirate ca restante și trimite email clientului';

    
    public function handle()
    {
        try {
            
            
            $invoices = Invoice::withoutGlobalScope('user')
                ->with('client')
                ->where('status', 'sent')
                ->whereDate('due_date', '<', today())
                ->get();

            
            foreach ($invoices as $invoice) {
                
                $invoice->update(['status' => 'overdue']);

                
                if ($invoice->client !== null && $invoice->client->email) {
                    Mail::to($invoice->client->email)->send(new InvoiceOverdueMail($invoice));
                }
            }

            
            $count = $invoices->count();

            
            if ($count === 1) {
                $mesaj = $count . ' factură marcată ca restantă.';
            } else {
                $mesaj = $count . ' facturi marcate ca restante.';
            }

            
            Log::info('invoices:check-overdue: ' . $mesaj);
            $this->info($mesaj);

            return self::SUCCESS;

        } catch (\Throwable $e) {
            
            Log::error('invoices:check-overdue a eșuat: ' . $e->getMessage(), ['exception' => $e]);
            $this->error('Eroare la procesare: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
