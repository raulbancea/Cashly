<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function view(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function markAsSent(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function markAsPaid(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function markAsCancelled(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function duplicate(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function sendEmail(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }

    public function downloadPdf(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }


}
