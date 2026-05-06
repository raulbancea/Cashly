<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/auth/google', [\App\Http\Controllers\GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleController::class, 'callback'])->name('auth.google.callback');

// Webhook public — fara auth, fara CSRF (exclus in bootstrap/app.php)
Route::post('/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');

Route::middleware(['auth', 'verified'])->group(function () {

    // Abonament — accesibile fara verificare abonament
    Route::get('/subscription', [StripeController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/checkout', [StripeController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/success', [StripeController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/portal', [StripeController::class, 'portal'])->name('subscription.portal');

    // Profil — accesibil fara abonament activ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // Toate celelalte rute necesita abonament activ (trial sau platit)
    Route::middleware(['subscription'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

        Route::resource('clients', \App\Http\Controllers\ClientController::class);
        Route::resource('products', \App\Http\Controllers\ProductController::class)->except(['show']);

        Route::get('invoices/export/csv', [\App\Http\Controllers\InvoiceController::class, 'exportCsv'])->name('invoices.exportCsv');
        Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
        Route::post('invoices/{invoice}/mark-as-sent', [\App\Http\Controllers\InvoiceController::class, 'markAsSent'])->name('invoices.markAsSent');
        Route::post('invoices/{invoice}/mark-as-paid', [\App\Http\Controllers\InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid');
        Route::post('invoices/{invoice}/mark-as-cancelled', [\App\Http\Controllers\InvoiceController::class, 'markAsCancelled'])->name('invoices.markAsCancelled');
        Route::post('invoices/{invoice}/send-email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
        Route::post('invoices/{invoice}/duplicate', [\App\Http\Controllers\InvoiceController::class, 'duplicate'])->name('invoices.duplicate');
        Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.downloadPdf');

        Route::get('expenses/export/csv', [\App\Http\Controllers\ExpenseController::class, 'exportCsv'])->name('expenses.exportCsv');
        Route::get('expenses/{expense}/receipt', [\App\Http\Controllers\ExpenseController::class, 'downloadReceipt'])->name('expenses.downloadReceipt');
        Route::resource('expenses', \App\Http\Controllers\ExpenseController::class)->except(['show']);

        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/categories', [\App\Http\Controllers\SettingsController::class, 'storeCategory'])->name('settings.categories.store');
        Route::delete('/settings/categories/{category}', [\App\Http\Controllers\SettingsController::class, 'destroyCategory'])->name('settings.categories.destroy');
    });
});

require __DIR__.'/auth.php';
