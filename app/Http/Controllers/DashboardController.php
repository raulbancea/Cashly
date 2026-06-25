<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        $lunaCurenta = now()->month;
        $anulCurent  = now()->year;

        $venituri = $user->invoices()
            ->where('status', 'paid')
            ->whereMonth('issue_date', $lunaCurenta)
            ->whereYear('issue_date', $anulCurent)
            ->selectRaw('currency, SUM(total_with_vat) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        if (isset($venituri['RON'])) {
            $revenue_ron = (float) $venituri['RON'];
        } else {
            $revenue_ron = 0;
        }

        if (isset($venituri['EUR'])) {
            $revenue_eur = (float) $venituri['EUR'];
        } else {
            $revenue_eur = 0;
        }

        $totalCheltuieli = $user->expenses()
            ->whereMonth('date', $lunaCurenta)
            ->whereYear('date', $anulCurent)
            ->selectRaw('currency, SUM(amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        if (isset($totalCheltuieli['RON'])) {
            $expenses_ron = (float) $totalCheltuieli['RON'];
        } else {
            $expenses_ron = 0;
        }

        if (isset($totalCheltuieli['EUR'])) {
            $expenses_eur = (float) $totalCheltuieli['EUR'];
        } else {
            $expenses_eur = 0;
        }

        $profit_ron = $revenue_ron - $expenses_ron;
        $profit_eur = $revenue_eur - $expenses_eur;

        $overdueCount = $user->invoices()->where('status', 'overdue')->count();

        $sixMonthsAgo = now()->startOfMonth()->subMonths(5);

        $invoiceRowsRaw = $user->invoices()
            ->where('status', 'paid')
            ->where('issue_date', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(issue_date) as year, MONTH(issue_date) as month, currency, SUM(total_with_vat) as total')
            ->groupBy('year', 'month', 'currency')
            ->get();

        $invoiceRowsIndexate = [];
        foreach ($invoiceRowsRaw as $row) {
            $cheie = $row->year . '-' . $row->month . '-' . $row->currency;
            $invoiceRowsIndexate[$cheie] = $row;
        }

        $expenseRowsRaw = $user->expenses()
            ->where('date', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(date) as year, MONTH(date) as month, currency, SUM(amount) as total')
            ->groupBy('year', 'month', 'currency')
            ->get();

        $expenseRowsIndexate = [];
        foreach ($expenseRowsRaw as $row) {
            $cheie = $row->year . '-' . $row->month . '-' . $row->currency;
            $expenseRowsIndexate[$cheie] = $row;
        }

        $cashFlow_ron = [];
        $cashFlow_eur = [];

        for ($i = 5; $i >= 0; $i--) {
            $data   = now()->startOfMonth()->subMonths($i);
            $an     = $data->year;
            $luna   = $data->month;
            $label  = $data->format('M Y');
            $cheie  = $an . '-' . $luna;

            $cheieRon = $cheie . '-RON';
            if (isset($invoiceRowsIndexate[$cheieRon])) {
                $venitRon = (float) $invoiceRowsIndexate[$cheieRon]->total;
            } else {
                $venitRon = 0;
            }

            if (isset($expenseRowsIndexate[$cheieRon])) {
                $cheltuialaRon = (float) $expenseRowsIndexate[$cheieRon]->total;
            } else {
                $cheltuialaRon = 0;
            }

            $cashFlow_ron[] = [
                'month'    => $label,
                'revenue'  => $venitRon,
                'expenses' => $cheltuialaRon,
            ];

            $cheieEur = $cheie . '-EUR';
            if (isset($invoiceRowsIndexate[$cheieEur])) {
                $venitEur = (float) $invoiceRowsIndexate[$cheieEur]->total;
            } else {
                $venitEur = 0;
            }

            if (isset($expenseRowsIndexate[$cheieEur])) {
                $cheltuialaEur = (float) $expenseRowsIndexate[$cheieEur]->total;
            } else {
                $cheltuialaEur = 0;
            }

            $cashFlow_eur[] = [
                'month'    => $label,
                'revenue'  => $venitEur,
                'expenses' => $cheltuialaEur,
            ];
        }

        $statusCounts = $user->invoices()
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status');

        if (isset($statusCounts['draft'])) {
            $nrDraft = (int) $statusCounts['draft'];
        } else {
            $nrDraft = 0;
        }

        if (isset($statusCounts['sent'])) {
            $nrSent = (int) $statusCounts['sent'];
        } else {
            $nrSent = 0;
        }

        if (isset($statusCounts['paid'])) {
            $nrPaid = (int) $statusCounts['paid'];
        } else {
            $nrPaid = 0;
        }

        if (isset($statusCounts['overdue'])) {
            $nrOverdue = (int) $statusCounts['overdue'];
        } else {
            $nrOverdue = 0;
        }

        if (isset($statusCounts['cancelled'])) {
            $nrCancelled = (int) $statusCounts['cancelled'];
        } else {
            $nrCancelled = 0;
        }

        $invoiceStatusCounts = [
            'draft'     => $nrDraft,
            'sent'      => $nrSent,
            'paid'      => $nrPaid,
            'overdue'   => $nrOverdue,
            'cancelled' => $nrCancelled,
        ];

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
