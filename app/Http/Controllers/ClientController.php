<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        $clients = auth()->user()->clients()->withCount('invoices')->latest()->paginate(15);
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
            'website' => 'nullable|url|max:255',
            'status'  => 'required|in:active,prospect,inactive',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('client-avatars/' . auth()->id(), 'public');
        }

        auth()->user()->clients()->create(array_merge($validated, ['avatar' => $avatarPath]));

        return redirect()->route('clients.index')->with('success', 'Client adăugat cu succes!');
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);

        $invoices = $client->invoices()->latest()->paginate(20);

        // KPI-uri calculate cu SQL aggregates per valuta
        $kpi = [];
        foreach (['RON', 'EUR'] as $currency) {
            $row = $client->invoices()
                ->where('currency', $currency)
                ->selectRaw("
                    COUNT(*) as total_count,
                    SUM(CASE WHEN total_with_vat > 0 THEN total_with_vat ELSE total END) as total_facturat,
                    SUM(CASE WHEN status = 'paid' THEN (CASE WHEN total_with_vat > 0 THEN total_with_vat ELSE total END) ELSE 0 END) as total_incasat,
                    SUM(CASE WHEN status IN ('sent','overdue') THEN (CASE WHEN total_with_vat > 0 THEN total_with_vat ELSE total END) ELSE 0 END) as total_restant,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as count_platite,
                    SUM(CASE WHEN status IN ('sent','overdue') THEN 1 ELSE 0 END) as count_asteptare
                ")
                ->first();

            if ($row && $row->total_facturat > 0) {
                $kpi[$currency] = [
                    'total_facturat'  => (float) $row->total_facturat,
                    'total_incasat'   => (float) $row->total_incasat,
                    'total_restant'   => (float) $row->total_restant,
                    'total_count'     => (int) $row->total_count,
                    'count_platite'   => (int) $row->count_platite,
                    'count_asteptare' => (int) $row->count_asteptare,
                ];
            }
        }

        return view('clients.show', compact('client', 'invoices', 'kpi'));
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'cui'            => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:500',
            'website'        => 'nullable|url|max:255',
            'status'         => 'required|in:active,prospect,inactive',
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_avatar'  => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_avatar') && $client->avatar) {
            Storage::disk('public')->delete($client->avatar);
            $validated['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            if ($client->avatar) {
                Storage::disk('public')->delete($client->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('client-avatars/' . auth()->id(), 'public');
        } else {
            unset($validated['avatar']);
        }

        unset($validated['remove_avatar']);
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Client actualizat!');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        if ($client->invoices()->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Nu poți șterge un client care are facturi asociate. Șterge mai întâi facturile.');
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client șters!');
    }
}
