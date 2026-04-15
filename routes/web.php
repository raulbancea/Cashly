<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::post('invoices/{invoice}/mark-as-sent', [\App\Http\Controllers\InvoiceController::class, 'markAsSent'])->name('invoices.markAsSent');
    Route::post('invoices/{invoice}/mark-as-paid', [\App\Http\Controllers\InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid');
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.downloadPdf');
    Route::get('invoices/export/csv', [\App\Http\Controllers\InvoiceController::class, 'exportCsv'])->name('invoices.exportCsv');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::get('expenses/export/csv', [\App\Http\Controllers\ExpenseController::class, 'exportCsv'])->name('expenses.exportCsv');

    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/categories', [\App\Http\Controllers\SettingsController::class, 'storeCategory'])->name('settings.categories.store');
    Route::delete('/settings/categories/{category}', [\App\Http\Controllers\SettingsController::class, 'destroyCategory'])->name('settings.categories.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
