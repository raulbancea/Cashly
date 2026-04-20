<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueInvoices extends Command
{
    protected $signature = 'invoices:check-overdue';
    protected $description = 'Marchează facturile trimise și expirate ca restante (overdue)';

    public function handle(): int
    {
        try {
            $count = Invoice::query()
                ->where('status', 'sent')
                ->whereDate('due_date', '<', today())
                ->update(['status' => 'overdue']);

            Log::info("invoices:check-overdue: {$count} " . ($count === 1 ? 'factură marcată' : 'facturi marcate') . ' ca restante.');
            $this->info("{$count} " . ($count === 1 ? 'factură marcată' : 'facturi marcate') . ' ca restante.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('invoices:check-overdue a eșuat: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            $this->error('Eroare la procesare: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
