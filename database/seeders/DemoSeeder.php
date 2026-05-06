<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // UserObserver creează automat cele 8 categorii de cheltuieli
        $user = User::create([
            'name'              => 'Andrei Popescu',
            'email'             => 'demo@cashly.ro',
            'password'          => Hash::make('demo1234'),
            'company_name'      => 'Popescu Design SRL',
            'company_vat'       => 'RO12345678',
            'currency'          => 'RON',
            'bank_account'      => 'RO49AAAA1B31007593840000',
            'address'           => 'Str. Mihai Eminescu 12, București',
            'phone'             => '+40722123456',
            'plan'              => 'free',
            'email_verified_at' => now(),
        ]);

        // Clienți
        $techstart = Client::create([
            'user_id' => $user->id,
            'name'    => 'TechStart SRL',
            'cui'     => 'RO23456789',
            'email'   => 'contact@techstart.ro',
            'phone'   => '+40731234567',
            'address' => 'Str. Victoriei 45, Cluj-Napoca',
            'status'  => 'active',
        ]);

        $mediapro = Client::create([
            'user_id' => $user->id,
            'name'    => 'MediaPro Solutions',
            'cui'     => 'RO34567890',
            'email'   => 'office@mediapro.ro',
            'phone'   => '+40742345678',
            'address' => 'Bd. Unirii 20, Timișoara',
            'status'  => 'active',
        ]);

        $bogdan = Client::create([
            'user_id' => $user->id,
            'name'    => 'Bogdan Ionescu',
            'email'   => 'bogdan.ionescu@gmail.com',
            'phone'   => '+40753456789',
            'status'  => 'active',
        ]);

        $euroimport = Client::create([
            'user_id' => $user->id,
            'name'    => 'Euro Import SRL',
            'cui'     => 'RO45678901',
            'email'   => 'import@euroimport.ro',
            'phone'   => '+40764567890',
            'address' => 'Str. Industriilor 8, Brașov',
            'status'  => 'prospect',
        ]);

        // Produse din catalog
        foreach ([
            ['name' => 'Dezvoltare website',   'category' => 'Web',          'price' => 2500.00, 'currency' => 'RON'],
            ['name' => 'Design grafic',         'category' => 'Design',       'price' => 800.00,  'currency' => 'RON'],
            ['name' => 'Consultanță IT',        'category' => 'Consultanță',  'price' => 150.00,  'currency' => 'RON'],
            ['name' => 'Mentenanță lunară',     'category' => 'Servicii',     'price' => 500.00,  'currency' => 'RON'],
        ] as $p) {
            Product::create(array_merge($p, ['user_id' => $user->id]));
        }

        // Facturi
        $invoices = [
            // PAID - luna 5 în urmă
            ['client' => $techstart, 'number' => 'CASH-2026-001', 'issue_date' => now()->subMonths(5)->startOfMonth()->addDays(2), 'due_date' => now()->subMonths(5)->startOfMonth()->addDays(32), 'status' => 'paid', 'currency' => 'RON', 'vat_rate' => 19,
                'items' => [['description' => 'Dezvoltare website corporate', 'quantity' => 1, 'unit_price' => 2500], ['description' => 'Design logo și identitate vizuală', 'quantity' => 1, 'unit_price' => 800]]],

            // PAID - luna 4 în urmă
            ['client' => $mediapro, 'number' => 'CASH-2026-002', 'issue_date' => now()->subMonths(4)->startOfMonth()->addDays(5), 'due_date' => now()->subMonths(4)->startOfMonth()->addDays(35), 'status' => 'paid', 'currency' => 'RON', 'vat_rate' => 19,
                'items' => [['description' => 'Campanie social media', 'quantity' => 1, 'unit_price' => 1200], ['description' => 'Creare conținut video', 'quantity' => 4, 'unit_price' => 250]]],

            // PAID - luna 3 în urmă, EUR fără TVA
            ['client' => $bogdan, 'number' => 'CASH-2026-003', 'issue_date' => now()->subMonths(3)->startOfMonth()->addDays(1), 'due_date' => now()->subMonths(3)->startOfMonth()->addDays(16), 'status' => 'paid', 'currency' => 'EUR', 'vat_rate' => null,
                'items' => [['description' => 'Consultanță IT - 10 ore', 'quantity' => 10, 'unit_price' => 80]]],

            // PAID - luna 2 în urmă
            ['client' => $techstart, 'number' => 'CASH-2026-004', 'issue_date' => now()->subMonths(2)->startOfMonth()->addDays(3), 'due_date' => now()->subMonths(2)->startOfMonth()->addDays(33), 'status' => 'paid', 'currency' => 'RON', 'vat_rate' => 19,
                'items' => [['description' => 'Mentenanță website', 'quantity' => 1, 'unit_price' => 500], ['description' => 'Optimizare SEO', 'quantity' => 1, 'unit_price' => 700]]],

            // OVERDUE - depășit cu 30 zile
            ['client' => $mediapro, 'number' => 'CASH-2026-005', 'issue_date' => now()->subDays(60), 'due_date' => now()->subDays(30), 'status' => 'overdue', 'currency' => 'RON', 'vat_rate' => 19,
                'items' => [['description' => 'Campanie email marketing Q1', 'quantity' => 1, 'unit_price' => 1800]]],

            // OVERDUE - EUR
            ['client' => $euroimport, 'number' => 'CASH-2026-006', 'issue_date' => now()->subDays(45), 'due_date' => now()->subDays(15), 'status' => 'overdue', 'currency' => 'EUR', 'vat_rate' => null,
                'items' => [['description' => 'Audit tehnic platformă e-commerce', 'quantity' => 1, 'unit_price' => 350], ['description' => 'Raport cu recomandări', 'quantity' => 1, 'unit_price' => 150]]],

            // SENT - scadent peste 20 zile
            ['client' => $techstart, 'number' => 'CASH-2026-007', 'issue_date' => now()->subDays(10), 'due_date' => now()->addDays(20), 'status' => 'sent', 'currency' => 'RON', 'vat_rate' => 19,
                'items' => [['description' => 'Mentenanță website - Aprilie', 'quantity' => 1, 'unit_price' => 500], ['description' => 'Actualizare design pagini produse', 'quantity' => 3, 'unit_price' => 200]]],

            // SENT - EUR
            ['client' => $bogdan, 'number' => 'CASH-2026-008', 'issue_date' => now()->subDays(5), 'due_date' => now()->addDays(25), 'status' => 'sent', 'currency' => 'EUR', 'vat_rate' => null,
                'items' => [['description' => 'Consultanță IT - 8 ore', 'quantity' => 8, 'unit_price' => 80]]],

            // DRAFT
            ['client' => $mediapro, 'number' => 'CASH-2026-009', 'issue_date' => now(), 'due_date' => now()->addDays(30), 'status' => 'draft', 'currency' => 'RON', 'vat_rate' => 19,
                'items' => [['description' => 'Strategie comunicare Q2 2026', 'quantity' => 1, 'unit_price' => 2200]]],

            // CANCELLED
            ['client' => $euroimport, 'number' => 'CASH-2026-010', 'issue_date' => now()->subMonths(3)->startOfMonth(), 'due_date' => now()->subMonths(3)->startOfMonth()->addDays(30), 'status' => 'cancelled', 'currency' => 'RON', 'vat_rate' => null,
                'items' => [['description' => 'Consultanță strategică', 'quantity' => 1, 'unit_price' => 1500]]],
        ];

        foreach ($invoices as $data) {
            $total = collect($data['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $vatAmount = $data['vat_rate'] ? round($total * $data['vat_rate'] / 100, 2) : 0;

            $invoice = Invoice::create([
                'user_id'        => $user->id,
                'client_id'      => $data['client']->id,
                'number'         => $data['number'],
                'issue_date'     => $data['issue_date'],
                'due_date'       => $data['due_date'],
                'status'         => $data['status'],
                'currency'       => $data['currency'],
                'vat_rate'       => $data['vat_rate'],
                'vat_amount'     => $vatAmount,
                'total'          => $total,
                'total_with_vat' => $total + $vatAmount,
            ]);

            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total'       => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }

        // Cheltuieli - categoriile sunt create de UserObserver
        $categories = $user->expenseCategories()->get()->keyBy('name');

        $expenses = [
            ['description' => 'Adobe Creative Cloud - abonament lunar', 'amount' => 249.00,  'currency' => 'RON', 'date' => now()->subMonths(5)->addDays(5),  'category' => 'Software / Abonamente'],
            ['description' => 'Uber - deplasare client Cluj',           'amount' => 67.50,   'currency' => 'RON', 'date' => now()->subMonths(4)->addDays(8),  'category' => 'Transport'],
            ['description' => 'Prânz de lucru cu clientul',             'amount' => 145.00,  'currency' => 'RON', 'date' => now()->subMonths(4)->addDays(10), 'category' => 'Masă / Restaurant'],
            ['description' => 'GitHub Pro - abonament anual',           'amount' => 42.00,   'currency' => 'EUR', 'date' => now()->subMonths(3)->addDays(2),  'category' => 'Software / Abonamente'],
            ['description' => 'Papetărie și rechizite birou',           'amount' => 89.00,   'currency' => 'RON', 'date' => now()->subMonths(3)->addDays(12), 'category' => 'Birou / Papetărie'],
            ['description' => 'Google Ads - campanie promovare',        'amount' => 320.00,  'currency' => 'RON', 'date' => now()->subMonths(2)->addDays(1),  'category' => 'Marketing'],
            ['description' => 'Internet fibră optică',                  'amount' => 59.99,   'currency' => 'RON', 'date' => now()->subMonths(2)->addDays(15), 'category' => 'Utilități'],
            ['description' => 'Adobe Creative Cloud - abonament lunar', 'amount' => 249.00,  'currency' => 'RON', 'date' => now()->subMonths(1)->addDays(5),  'category' => 'Software / Abonamente'],
            ['description' => 'Bilet tren București-Cluj',              'amount' => 115.00,  'currency' => 'RON', 'date' => now()->subMonths(1)->addDays(20), 'category' => 'Transport'],
            ['description' => 'Cină de afaceri',                       'amount' => 210.00,  'currency' => 'RON', 'date' => now()->subDays(15),               'category' => 'Masă / Restaurant'],
            ['description' => 'Figma - abonament Pro',                 'amount' => 15.00,   'currency' => 'EUR', 'date' => now()->subDays(10),               'category' => 'Software / Abonamente'],
            ['description' => 'Internet fibră optică',                  'amount' => 59.99,   'currency' => 'RON', 'date' => now()->subDays(5),                'category' => 'Utilități'],
        ];

        foreach ($expenses as $e) {
            Expense::create([
                'user_id'     => $user->id,
                'category_id' => $categories->get($e['category'])?->id,
                'description' => $e['description'],
                'amount'      => $e['amount'],
                'currency'    => $e['currency'],
                'date'        => $e['date'],
            ]);
        }
    }
}
