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

        // KPI-uri calculate cu SQL aggregates per valuta
        $kpi = [];
        foreach (['RON', 'EUR'] as $currency) {
            $row = $client->invoices()
                ->where('currency', $currency)
                ->selectRaw("
                    SUM(CASE WHEN total_with_vat > 0 THEN total_with_vat ELSE total END) as total_facturat,
                    SUM(CASE WHEN status = 'paid' THEN (CASE WHEN total_with_vat > 0 THEN total_with_vat ELSE total END) ELSE 0 END) as total_incasat,
                    SUM(CASE WHEN status IN ('sent','overdue') THEN (CASE WHEN total_with_vat > 0 THEN total_with_vat ELSE total END) ELSE 0 END) as total_restant
                ")
                ->first();

            if ($row && $row->total_facturat > 0) {
                $kpi[$currency] = [
                    'total_facturat' => (float) $row->total_facturat,
                    'total_incasat'  => (float) $row->total_incasat,
                    'total_restant'  => (float) $row->total_restant,
                ];
            }
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
