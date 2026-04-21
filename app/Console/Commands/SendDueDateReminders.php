<?php

namespace App\Console\Commands;

use App\Mail\InvoiceReminderMail;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDueDateReminders extends Command
{
    protected $signature = 'invoices:send-reminders';
    protected $description = 'Trimite email de reminder pentru facturile scadente în 3 zile';

    public function handle(): int
    {
        try {
            $invoices = Invoice::withoutGlobalScope('user')
                ->with('client')
                ->where('status', 'sent')
                ->whereDate('due_date', '>=', today()->addDays(1))
                ->whereDate('due_date', '<=', today()->addDays(3))
                ->whereNull('reminder_sent_at')
                ->get();

            $sent = 0;
            foreach ($invoices as $invoice) {
                if (!$invoice->client?->email) {
                    continue;
                }
                try {
                    $daysLeft = (int) today()->diffInDays($invoice->due_date);
                    Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice, $daysLeft));
                    $invoice->updateQuietly(['reminder_sent_at' => now()]);
                    $sent++;
                } catch (\Throwable $e) {
                    Log::error("Reminder email failed for invoice {$invoice->id}: " . $e->getMessage());
                }
            }

            Log::info("invoices:send-reminders: {$sent} reminder-uri trimise.");
            $this->info("{$sent} reminder-uri trimise.");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('invoices:send-reminders a eșuat: ' . $e->getMessage(), ['exception' => $e]);
            $this->error('Eroare: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
