<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products()->latest()->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',
            'currency'    => 'required|in:RON,EUR',
            'description' => 'nullable|string|max:1000',
        ]);

        auth()->user()->products()->create($validated);

        return redirect()->route('products.index')->with('success', 'Produs adăugat cu succes!');
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',
            'currency'    => 'required|in:RON,EUR',
            'description' => 'nullable|string|max:1000',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produs actualizat!');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produs șters!');
    }
}
