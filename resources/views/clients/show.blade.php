<x-cashly-layout>
    <x-slot name="title">{{ $client->name }}</x-slot>

    {{-- Header --}}
    <div class="flex items-start justify-between mb-4">
        <div>
            <div class="flex items-center gap-3">
                @if($client->avatar)
                    <img src="{{ Storage::disk('public')->url($client->avatar) }}"
                         alt="{{ $client->name }}"
                         class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                @else
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 bg-teal-500">
                        {{ strtoupper(mb_substr($client->name, 0, 1)) }}
                    </div>
                @endif
                <h2 class="text-xl font-bold text-gray-900">{{ $client->name }}</h2>
                <span class="px-2.5 py-1 text-xs font-medium rounded-full
                    {{ $client->status === 'active'   ? 'bg-green-100 text-green-700'  : '' }}
                    {{ $client->status === 'prospect' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $client->status === 'inactive' ? 'bg-gray-100 text-gray-500'    : '' }}">
                    {{ $client->status === 'active' ? 'Activ' : ($client->status === 'prospect' ? 'Prospect' : 'Inactiv') }}
                </span>
            </div>
            @if($client->cui)
                <p class="mt-1 text-sm text-gray-500">CUI: {{ $client->cui }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('clients.index') }}"
               class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                ← Înapoi la clienți
            </a>
            <a href="{{ route('clients.edit', $client) }}"
               class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Editează
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 mb-4 lg:grid-cols-3">

        {{-- Date contact --}}
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="mb-4 text-xs font-semibold tracking-wide text-gray-400 uppercase">Date contact</h3>
            <div class="space-y-3">
                @if($client->email)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 text-gray-400 text-sm">✉</span>
                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <a href="mailto:{{ $client->email }}"
                               class="text-sm text-teal-600 hover:underline">{{ $client->email }}</a>
                        </div>
                    </div>
                @endif
                @if($client->phone)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 text-gray-400 text-sm">☎</span>
                        <div>
                            <p class="text-xs text-gray-400">Telefon</p>
                            <p class="text-sm text-gray-700">{{ $client->phone }}</p>
                        </div>
                    </div>
                @endif
                @if($client->website)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 text-gray-400 text-sm">🌐</span>
                        <div>
                            <p class="text-xs text-gray-400">Website</p>
                            <a href="{{ $client->website }}" target="_blank" rel="noopener noreferrer"
                               class="text-sm text-teal-600 hover:underline truncate block max-w-[180px]">
                                {{ preg_replace('#^https?://#', '', rtrim($client->website, '/')) }}
                            </a>
                        </div>
                    </div>
                @endif
                @if($client->address)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 text-gray-400 text-sm">⌂</span>
                        <div>
                            <p class="text-xs text-gray-400">Adresă</p>
                            <p class="text-sm text-gray-700">{{ $client->address }}</p>
                        </div>
                    </div>
                @endif
                @if(!$client->email && !$client->phone && !$client->website && !$client->address)
                    <p class="text-sm text-gray-400">Nicio informație de contact adăugată.</p>
                @endif
            </div>
        </div>

        {{-- KPI-uri --}}
        <div class="lg:col-span-2">
            @if(empty($kpi))
                <div class="flex items-center justify-center h-full p-6 bg-white border border-gray-200 rounded-xl">
                    <p class="text-sm text-gray-400">Nicio factură emisă pentru acest client.</p>
                </div>
            @else
                @foreach($kpi as $currency => $valori)
                    <div class="grid grid-cols-3 gap-4 {{ !$loop->first ? 'mt-4' : '' }}">
                        {{-- Total facturat --}}
                        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                            <p class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">Total facturat</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ number_format($valori['total_facturat'], 2, ',', '.') }}
                                <span class="text-base font-medium text-gray-400">{{ $currency }}</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">{{ $valori['total_count'] }} facturi</p>
                        </div>

                        {{-- Total incasat --}}
                        <div class="p-5 bg-white border border-green-100 rounded-xl">
                            <p class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">Încasat</p>
                            <p class="text-2xl font-bold text-green-700">
                                {{ number_format($valori['total_incasat'], 2, ',', '.') }}
                                <span class="text-base font-medium text-green-400">{{ $currency }}</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                {{ $valori['count_platite'] }} facturi plătite
                            </p>
                        </div>

                        {{-- Total restant --}}
                        <div class="p-5 bg-white rounded-xl" style="border: 1px solid {{ $valori['total_restant'] > 0 ? '#fecaca' : '#e5e7eb' }}">
                            <p class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">Neîncasat</p>
                            <p class="text-2xl font-bold {{ $valori['total_restant'] > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                {{ number_format($valori['total_restant'], 2, ',', '.') }}
                                <span class="text-base font-medium {{ $valori['total_restant'] > 0 ? 'text-red-300' : 'text-gray-300' }}">{{ $currency }}</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                {{ $valori['count_asteptare'] }} facturi în așteptare
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Tabel facturi --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Facturi emise</h3>
            @if($invoices->isNotEmpty())
                <a href="{{ route('invoices.create') }}"
                   class="px-3 py-1.5 text-sm font-medium text-teal-600 border border-teal-200 rounded-lg hover:bg-teal-50">
                    + Factură nouă
                </a>
            @endif
        </div>

        @if($invoices->isEmpty())
            <div class="px-6 py-10 text-center">
                <p class="mb-3 text-sm text-gray-400">Nicio factură emisă pentru acest client.</p>
                <a href="{{ route('invoices.create') }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Creează prima factură
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Număr</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Data emiterii</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Scadență</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Total</th>
                            <th class="px-5 py-2.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    {{ $invoice->number }}
                                </td>
                                <td class="px-6 py-3 text-gray-600">
                                    {{ $invoice->issue_date->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-3 text-gray-600">
                                    {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                        {{ $invoice->status === 'draft'     ? 'bg-gray-100 text-gray-600'    : '' }}
                                        {{ $invoice->status === 'sent'      ? 'bg-blue-100 text-blue-700'    : '' }}
                                        {{ $invoice->status === 'paid'      ? 'bg-green-100 text-green-700'  : '' }}
                                        {{ $invoice->status === 'overdue'   ? 'bg-red-100 text-red-700'      : '' }}
                                        {{ $invoice->status === 'cancelled' ? 'bg-orange-100 text-orange-700': '' }}">
                                        {{ ['draft' => 'Draft', 'sent' => 'Trimisă', 'paid' => 'Încasată', 'overdue' => 'Restantă', 'cancelled' => 'Anulată'][$invoice->status] ?? $invoice->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 font-semibold text-right text-gray-900">
                                    {{ number_format($invoice->effective_total, 2, ',', '.') }}
                                    <span class="text-xs font-normal text-gray-400">{{ $invoice->currency }}</span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="text-xs font-medium text-teal-600 hover:underline">
                                        Vezi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="px-5 py-3 border-t border-gray-100">
                    {{ $invoices->links() }}
                </div>
            @endif
        @endif
    </div>

</x-cashly-layout>
