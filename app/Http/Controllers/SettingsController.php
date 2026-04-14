<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $categories = $user->expenseCategories()->get();
        return view('settings.index', compact('user', 'categories'));
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_vat' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'currency' => 'required|in:RON,EUR',
        ]);

        auth()->user()->update($validate);
        return redirect()->route('settings.index')->with('success', 'Setarile au fost salvate!');
    }

    public function storeCategory(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        auth()->user()->expenseCategories()->create($validate);

        return redirect()->route('settings.index')->with('success', 'Categoria a fost adaugata!');
    }

    public function destroyCategory(\App\Models\ExpenseCategory $category)
    {
        $category->delete();
        return redirect()->route('settings.index')->with('success', 'Categoria a fost stearsa!');
    }
}
