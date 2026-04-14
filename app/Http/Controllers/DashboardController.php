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
            ->sum('total');

        $revenue_eur = (float) $user->invoices()
            ->where('status', 'paid')->where('currency', 'EUR')
            ->whereMonth('issue_date', $currentMonth)->whereYear('issue_date', $currentYear)
            ->sum('total');

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

        $cashFlow_ron = [];
        $cashFlow_eur = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year  = $date->year;
            $label = $date->format('M Y');

            $cashFlow_ron[] = [
                'month'    => $label,
                'revenue'  => (float) $user->invoices()->where('status', 'paid')->where('currency', 'RON')->whereMonth('issue_date', $month)->whereYear('issue_date', $year)->sum('total'),
                'expenses' => (float) $user->expenses()->where('currency', 'RON')->whereMonth('date', $month)->whereYear('date', $year)->sum('amount'),
            ];

            $cashFlow_eur[] = [
                'month'    => $label,
                'revenue'  => (float) $user->invoices()->where('status', 'paid')->where('currency', 'EUR')->whereMonth('issue_date', $month)->whereYear('issue_date', $year)->sum('total'),
                'expenses' => (float) $user->expenses()->where('currency', 'EUR')->whereMonth('date', $month)->whereYear('date', $year)->sum('amount'),
            ];
        }

        return view('dashboard', compact(
            'revenue_ron', 'revenue_eur',
            'expenses_ron', 'expenses_eur',
            'profit_ron', 'profit_eur',
            'overdueCount', 'cashFlow_ron', 'cashFlow_eur'
        ));
    }
}
