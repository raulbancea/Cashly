<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $revenue_ron = (float) $user->invoices()
            ->where('status', 'paid')->where('currency', 'RON')
            ->whereMonth('issue_date', $currentMonth)->whereYear('issue_date', $currentYear)
            ->sum('total_with_vat');

        $revenue_eur = (float) $user->invoices()
            ->where('status', 'paid')->where('currency', 'EUR')
            ->whereMonth('issue_date', $currentMonth)->whereYear('issue_date', $currentYear)
            ->sum('total_with_vat');

        $expenses_ron = (float) $user->expenses()
            ->where('currency', 'RON')
            ->whereMonth('date', $currentMonth)->whereYear('date', $currentYear)
            ->sum('amount');

        $expenses_eur = (float) $user->expenses()
            ->where('currency', 'EUR')
            ->whereMonth('date', $currentMonth)->whereYear('date', $currentYear)
            ->sum('amount');

        $profit_ron = $revenue_ron - $expenses_ron;
        $profit_eur = $revenue_eur - $expenses_eur;

        $overdueCount = $user->invoices()->where('status', 'overdue')->count();

        $sixMonthsAgo = now()->startOfMonth()->subMonths(5);

        // Un singur query per valuta pentru venituri (facturi platite, ultimele 6 luni)
        $invoiceRows = $user->invoices()
            ->where('status', 'paid')
            ->where('issue_date', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(issue_date) as year, MONTH(issue_date) as month, currency, SUM(total_with_vat) as total')
            ->groupBy('year', 'month', 'currency')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month . '-' . $r->currency);

        // Un singur query per valuta pentru cheltuieli (ultimele 6 luni)
        $expenseRows = $user->expenses()
            ->where('date', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(date) as year, MONTH(date) as month, currency, SUM(amount) as total')
            ->groupBy('year', 'month', 'currency')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month . '-' . $r->currency);

        $cashFlow_ron = [];
        $cashFlow_eur = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $year  = $date->year;
            $month = $date->month;
            $label = $date->format('M Y');
            $key   = $year . '-' . $month;

            $cashFlow_ron[] = [
                'month'    => $label,
                'revenue'  => (float) ($invoiceRows->get("{$key}-RON")?->total ?? 0),
                'expenses' => (float) ($expenseRows->get("{$key}-RON")?->total ?? 0),
            ];

            $cashFlow_eur[] = [
                'month'    => $label,
                'revenue'  => (float) ($invoiceRows->get("{$key}-EUR")?->total ?? 0),
                'expenses' => (float) ($expenseRows->get("{$key}-EUR")?->total ?? 0),
            ];
        }

        // Breakdown status facturi (total, nu doar luna curenta)
        $statusCounts = $user->invoices()
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $invoiceStatusCounts = [
            'draft'     => (int) ($statusCounts['draft']     ?? 0),
            'sent'      => (int) ($statusCounts['sent']      ?? 0),
            'paid'      => (int) ($statusCounts['paid']      ?? 0),
            'overdue'   => (int) ($statusCounts['overdue']   ?? 0),
            'cancelled' => (int) ($statusCounts['cancelled'] ?? 0),
        ];

        // Ultimele 5 facturi
        $recentInvoices = $user->invoices()
            ->with('client')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'revenue_ron', 'revenue_eur',
            'expenses_ron', 'expenses_eur',
            'profit_ron', 'profit_eur',
            'overdueCount', 'cashFlow_ron', 'cashFlow_eur',
            'invoiceStatusCounts', 'recentInvoices'
        ));
    }
}
