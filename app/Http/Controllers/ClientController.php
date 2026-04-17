<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = auth()->user()->clients()->latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'cui'     => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,prospect,inactive',
        ]);

        auth()->user()->clients()->create($validated);

        return redirect()->route('clients.index')->with('success', 'Client adăugat cu succes!');
    }

        public function show(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403);
        }

        $invoices = $client->invoices()->latest()->get();

        // KPI-uri calculate per valuta
        $kpi = [];
        foreach (['RON', 'EUR'] as $currency) {
            $subset = $invoices->where('currency', $currency);
            if ($subset->isEmpty()) continue;

            $sum = fn($col) => $col->sum(fn($i) => (float) ($i->total_with_vat > 0 ? $i->total_with_vat : $i->total));

            $kpi[$currency] = [
                'total_facturat' => $sum($subset),
                'total_incasat'  => $sum($subset->where('status', 'paid')),
                'total_restant'  => $sum($subset->whereIn('status', ['sent', 'overdue'])),
            ];
        }

        return view('clients.show', compact('client', 'invoices', 'kpi'));
    }

    public function edit(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403);
        }
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'cui'     => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,prospect,inactive',
        ]);

        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Client actualizat!');
    }

    public function destroy(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            abort(403);
        }
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client șters!');
    }
}
