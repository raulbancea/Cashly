<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user         = auth()->user();
        $selectedYear = (int) $request->get('an', date('Y'));

        // Ani disponibili pentru selector
        $yearsFromInvoices = $user->invoices()
            ->selectRaw('YEAR(issue_date) as an')->distinct()->pluck('an');
        $yearsFromExpenses = $user->expenses()
            ->selectRaw('YEAR(date) as an')->distinct()->pluck('an');

        $availableYears = $yearsFromInvoices
            ->merge($yearsFromExpenses)
            ->push($selectedYear)
            ->unique()
            ->sortDesc()
            ->values();

        // ── 1. Date lunare pentru graficul bar ────────────────────────────────
        $monthLabels = ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun',
                        'Iul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $revenueRows = $user->invoices()
            ->where('status', 'paid')
            ->whereYear('issue_date', $selectedYear)
            ->selectRaw('MONTH(issue_date) as month, currency, SUM(total_with_vat) as total')
            ->groupBy('month', 'currency')
            ->get()
            ->keyBy(fn($r) => $r->month . '-' . $r->currency);

        $expenseRows = $user->expenses()
            ->whereYear('date', $selectedYear)
            ->selectRaw('MONTH(date) as month, currency, SUM(amount) as total')
            ->groupBy('month', 'currency')
            ->get()
            ->keyBy(fn($r) => $r->month . '-' . $r->currency);

        $monthly = [];
        foreach (['RON', 'EUR'] as $currency) {
            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $data[] = [
                    'month'    => $monthLabels[$m - 1],
                    'revenue'  => (float) ($revenueRows->get("{$m}-{$currency}")->total ?? 0),
                    'expenses' => (float) ($expenseRows->get("{$m}-{$currency}")->total ?? 0),
                ];
            }
            if (collect($data)->contains(fn($d) => $d['revenue'] > 0 || $d['expenses'] > 0)) {
                $monthly[$currency] = $data;
            }
        }

        // ── 2. TVA colectat per cotă și monedă (doar facturi paid) ───────────
        $vatByRate = $user->invoices()
            ->where('status', 'paid')
            ->whereYear('issue_date', $selectedYear)
            ->whereNotNull('vat_rate')
            ->where('vat_amount', '>', 0)
            ->selectRaw('vat_rate, currency, SUM(vat_amount) as total_vat, COUNT(*) as numar_facturi')
            ->groupBy('vat_rate', 'currency')
            ->orderBy('currency')
            ->orderBy('vat_rate')
            ->get()
            ->map(fn($r) => [
                'cota'            => (int) round((float) $r->vat_rate),
                'currency'        => $r->currency,
                'total_vat'       => (float) $r->total_vat,
                'numar_facturi'   => (int) $r->numar_facturi,
            ]);

        $vatTotalByCurrency = $vatByRate->groupBy('currency')->map(fn($rows) => $rows->sum('total_vat'));
        $totalVatColectat = $vatByRate->sum('total_vat');

        // ── 3. Cheltuieli grupate pe categorie (pentru pie chart) ─────────────
        $allExpenses = $user->expenses()
            ->whereYear('date', $selectedYear)
            ->with('category')
            ->get();

        $expensesByCategory = $allExpenses
            ->groupBy(fn($e) => $e->category?->name ?? 'Fără categorie')
            ->map(fn($group, $name) => [
                'name'  => $name,
                'color' => $group->first()->category?->color ?? '#94a3b8',
                'ron'   => round($group->where('currency', 'RON')->sum('amount'), 2),
                'eur'   => round($group->where('currency', 'EUR')->sum('amount'), 2),
            ])
            ->sortByDesc('ron')
            ->values();

        // ── 4. Totale anuale ──────────────────────────────────────────────────
        $totale = [];
        foreach (['RON', 'EUR'] as $currency) {
            $venituri   = (float) $user->invoices()
                ->where('status', 'paid')
                ->whereYear('issue_date', $selectedYear)
                ->where('currency', $currency)
                ->sum('total_with_vat');

            $cheltuieli = (float) $user->expenses()
                ->whereYear('date', $selectedYear)
                ->where('currency', $currency)
                ->sum('amount');

            if ($venituri > 0 || $cheltuieli > 0) {
                $totale[$currency] = [
                    'venituri'   => $venituri,
                    'cheltuieli' => $cheltuieli,
                    'profit'     => $venituri - $cheltuieli,
                ];
            }
        }

        return view('reports.index', compact(
            'selectedYear',
            'availableYears',
            'monthly',
            'vatByRate',
            'vatTotalByCurrency',
            'totalVatColectat',
            'expensesByCategory',
            'totale'
        ));
    }
}
