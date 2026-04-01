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

        $revenue = $user->invoices()
            ->where('status', 'paid')
            ->whereMonth('issue_date', $currentMonth)
            ->whereYear('issue_date', $currentYear)
            ->sum('total');

        $expenses = $user->expenses()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $profit = $revenue - $expenses;

        $overdueCount = $user->invoices()
            ->where('status', 'overdue')
            ->count();

        $cashFlow = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $monthRevenue = $user->invoices()
                ->where('status', 'paid')
                ->whereMonth('issue_date', $month)
                ->whereYear('issue_date', $year)
                ->sum('total');

            $monthExpenses = $user->expenses()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $cashFlow[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float) $monthRevenue,
                'expenses' => (float) $monthExpenses,
            ];
        }

        return view('dashboard', compact(
            'revenue', 'expenses', 'profit', 'overdueCount', 'cashFlow'
        ));
    }
}
