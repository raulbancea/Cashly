<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'name'         => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_vat'  => 'nullable|string|max:50',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:500',
            'currency'     => 'required|in:RON,EUR',
            'bank_account' => 'nullable|string|max:255',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'remove_logo'  => 'nullable|boolean',
        ]);

        $user = auth()->user();

        $data = $validate;
        unset($data['logo']);
        unset($data['remove_logo']);

        if ($request->boolean('remove_logo') && $user->logo) {

            Storage::disk('public')->delete($user->logo);
            $data['logo'] = null;
        }

        if ($request->hasFile('logo')) {

            if ($user->logo) {
                Storage::disk('public')->delete($user->logo);
            }

            $path = $request->file('logo')->store('logos/' . $user->id, 'public');
            $data['logo'] = $path;
        }

        $user->update($data);

        return redirect()->route('settings.index')->with('success', 'Setările au fost salvate!');
    }

    public function storeCategory(Request $request)
    {

        $validate = $request->validate([
            'name'        => 'required|string|max:255',
            'color'       => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'redirect_to' => 'nullable|string|max:500',
        ]);

        $category = auth()->user()->expenseCategories()->create([
            'name'  => $validate['name'],
            'color' => $validate['color'],
        ]);

        if (!empty($validate['redirect_to'])) {
            $parsed    = parse_url($validate['redirect_to']);
            $appParsed = parse_url(config('app.url'));

            $sameHost  = isset($parsed['host']) && $parsed['host'] === ($appParsed['host'] ?? '');

            $isRelative = !isset($parsed['host']);

            if ($sameHost || $isRelative) {

                $base = strtok($validate['redirect_to'], '?');

                return redirect($base . '?new_category_id=' . $category->id);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Categoria a fost adăugată!');
    }

    public function destroyCategory(\App\Models\ExpenseCategory $category)
    {

        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $category->delete();

        return redirect()->route('settings.index')->with('success', 'Categoria a fost stearsa!');
    }
}
