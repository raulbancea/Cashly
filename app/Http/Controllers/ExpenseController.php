<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = auth()->user()->expenses()->with('category')->latest()->get();
        $categories = auth()->user()->expenseCategories()->get();
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = auth()->user()->expenseCategories()->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'currency'    => 'required|in:RON,EUR',
            'date'        => 'required|date',
            'category_id' => 'nullable|exists:expense_categories,id',
        ]);

        auth()->user()->expenses()->create($validated);

        return redirect()->route('expenses.index')->with('success', 'Cheltuială adăugată cu succes!');
    }

    public function edit(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        $categories = auth()->user()->expenseCategories()->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'currency'    => 'required|in:RON,EUR',
            'date'        => 'required|date',
            'category_id' => 'nullable|exists:expense_categories,id',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Cheltuială actualizată!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Cheltuială ștearsă!');
    }
}
