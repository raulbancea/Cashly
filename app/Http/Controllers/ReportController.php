<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ReportController extends Controller
{
    
    public function index(Request $request)
    {
        
        $user = auth()->user();

        
        $selectedYear = (int) $request->get('an', date('Y'));

        
        $aniDinFacturi = $user->invoices()
            ->selectRaw('YEAR(issue_date) as an')
            ->distinct()
            ->pluck('an');

        
        $aniDinCheltuieli = $user->expenses()
            ->selectRaw('YEAR(date) as an')
            ->distinct()
            ->pluck('an');

        
        $availableYears = $aniDinFacturi->merge($aniDinCheltuieli);
        
        $availableYears->push($selectedYear);
        $availableYears = $availableYears->unique()->sortDesc()->values();

        
        $monthLabels = ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun',
                        'Iul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        
        $revenueRowsRaw = $user->invoices()
            ->where('status', 'paid')
            ->whereYear('issue_date', $selectedYear)
            ->selectRaw('MONTH(issue_date) as month, currency, SUM(total_with_vat) as total')
            ->groupBy('month', 'currency')
            ->get();

        
        $revenueRows = [];
        foreach ($revenueRowsRaw as $row) {
            $cheie = $row->month . '-' . $row->currency;
            $revenueRows[$cheie] = $row;
        }

        
        $expenseRowsRaw = $user->expenses()
            ->whereYear('date', $selectedYear)
            ->selectRaw('MONTH(date) as month, currency, SUM(amount) as total')
            ->groupBy('month', 'currency')
            ->get();

        
        $expenseRows = [];
        foreach ($expenseRowsRaw as $row) {
            $cheie = $row->month . '-' . $row->currency;
            $expenseRows[$cheie] = $row;
        }

        
        $monthly = [];
        foreach (['RON', 'EUR'] as $currency) {
            $data = [];

            
            for ($m = 1; $m <= 12; $m++) {
                $cheie = $m . '-' . $currency;

                
                if (isset($revenueRows[$cheie])) {
                    $venit = (float) $revenueRows[$cheie]->total;
                } else {
                    $venit = 0;
                }

                
                if (isset($expenseRows[$cheie])) {
                    $cheltuiala = (float) $expenseRows[$cheie]->total;
                } else {
                    $cheltuiala = 0;
                }

                $data[] = [
                    'month'    => $monthLabels[$m - 1],
                    'revenue'  => $venit,
                    'expenses' => $cheltuiala,
                ];
            }

            
            $areDate = false;
            foreach ($data as $linie) {
                if ($linie['revenue'] > 0 || $linie['expenses'] > 0) {
                    $areDate = true;
                    break;
                }
            }

            if ($areDate) {
                $monthly[$currency] = $data;
            }
        }

        
        $vatRawData = $user->invoices()
            ->where('status', 'paid')
            ->whereYear('issue_date', $selectedYear)
            ->whereNotNull('vat_rate')
            ->where('vat_amount', '>', 0)
            ->selectRaw('vat_rate, currency, SUM(vat_amount) as total_vat, COUNT(*) as numar_facturi')
            ->groupBy('vat_rate', 'currency')
            ->orderBy('currency')
            ->orderBy('vat_rate')
            ->get();

        
        $vatByRateArray = [];
        foreach ($vatRawData as $r) {
            $vatByRateArray[] = [
                'cota'          => (int) round((float) $r->vat_rate),
                'currency'      => $r->currency,
                'total_vat'     => (float) $r->total_vat,
                'numar_facturi' => (int) $r->numar_facturi,
            ];
        }
        
        $vatByRate = collect($vatByRateArray);

        
        $vatTotalByCurrency = [];
        foreach ($vatByRateArray as $rand) {
            $moneda = $rand['currency'];
            if (!isset($vatTotalByCurrency[$moneda])) {
                $vatTotalByCurrency[$moneda] = 0;
            }
            $vatTotalByCurrency[$moneda] += $rand['total_vat'];
        }

        
        $totalVatColectat = 0;
        foreach ($vatByRateArray as $rand) {
            $totalVatColectat += $rand['total_vat'];
        }

        
        $toateCheltuielile = $user->expenses()
            ->whereYear('date', $selectedYear)
            ->with('category')
            ->get();

        
        $cheltuieliPeCategorie = [];
        foreach ($toateCheltuielile as $cheltuiala) {
            
            
            if ($cheltuiala->category !== null) {
                $numeCategorie   = $cheltuiala->category->name;
                $culoareCategorie = $cheltuiala->category->color;
            } else {
                $numeCategorie   = 'Fara categorie';
                $culoareCategorie = '#94a3b8';
            }

            
            if (!isset($cheltuieliPeCategorie[$numeCategorie])) {
                $cheltuieliPeCategorie[$numeCategorie] = [
                    'name'  => $numeCategorie,
                    'color' => $culoareCategorie,
                    'ron'   => 0,
                    'eur'   => 0,
                ];
            }

            
            if ($cheltuiala->currency === 'RON') {
                $cheltuieliPeCategorie[$numeCategorie]['ron'] += (float) $cheltuiala->amount;
            } else {
                $cheltuieliPeCategorie[$numeCategorie]['eur'] += (float) $cheltuiala->amount;
            }
        }

        
        $expensesByCategoryArray = [];
        foreach ($cheltuieliPeCategorie as $categorie) {
            $categorie['ron'] = round($categorie['ron'], 2);
            $categorie['eur'] = round($categorie['eur'], 2);
            $expensesByCategoryArray[] = $categorie;
        }

        
        usort($expensesByCategoryArray, function ($a, $b) {
            if ($b['ron'] > $a['ron']) {
                return 1;
            } else {
                return -1;
            }
        });
        $expensesByCategory = collect($expensesByCategoryArray);

        
        $totale = [];
        foreach (['RON', 'EUR'] as $currency) {
            $venituri = (float) $user->invoices()
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
