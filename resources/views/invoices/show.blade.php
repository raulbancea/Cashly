<x-cashly-layout>
    <x-slot name="title">Factură {{ $invoice->number }}</x-slot>

    {{-- Modal: Marchează încasată --}}
    <div id="modal-paid" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1rem;padding:1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p style="font-weight:600;color:#111827;font-size:0.95rem;">Marchează ca încasată</p>
                    <p style="font-size:0.75rem;color:#6b7280;">{{ $invoice->number }}</p>
                </div>
            </div>
            <p style="font-size:0.875rem;color:#374151;margin-bottom:1.25rem;line-height:1.5;">Confirmi că factura <strong>{{ $invoice->number }}</strong> a fost încasată? Această acțiune nu poate fi anulată.</p>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button onclick="document.getElementById('modal-paid').style.display='none'" style="padding:0.5rem 1.25rem;font-size:0.875rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;color:#374151;cursor:pointer;">Anulează</button>
                <form method="POST" action="{{ route('invoices.markAsPaid', $invoice) }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="padding:0.5rem 1.25rem;font-size:0.875rem;border:none;border-radius:0.5rem;background:#16a34a;color:#fff;cursor:pointer;font-weight:500;">Confirmă</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Anulează factura --}}
    <div id="modal-cancel" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1rem;padding:1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </div>
                <div>
                    <p style="font-weight:600;color:#111827;font-size:0.95rem;">Anulează factura</p>
                    <p style="font-size:0.75rem;color:#6b7280;">{{ $invoice->number }}</p>
                </div>
            </div>
            <p style="font-size:0.875rem;color:#374151;margin-bottom:1.25rem;line-height:1.5;">Ești sigur că vrei să anulezi factura <strong>{{ $invoice->number }}</strong>? Această acțiune nu poate fi anulată.</p>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button onclick="document.getElementById('modal-cancel').style.display='none'" style="padding:0.5rem 1.25rem;font-size:0.875rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;color:#374151;cursor:pointer;">Înapoi</button>
                <form method="POST" action="{{ route('invoices.markAsCancelled', $invoice) }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="padding:0.5rem 1.25rem;font-size:0.875rem;border:none;border-radius:0.5rem;background:#ef4444;color:#fff;cursor:pointer;font-weight:500;">Anulează factura</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Trimite pe email --}}
    @if($clientEmail)
    <div id="modal-send-email" class="fixed inset-0 z-50 items-center justify-center bg-black/40" style="display:none">
        <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg mx-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-full flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Trimite factura pe email</h3>
                    <p class="text-xs text-gray-400">PDF atașat automat</p>
                </div>
            </div>
            <p class="mb-1 text-sm text-gray-600">
                Factura <strong class="text-gray-900">{{ $invoice->number }}</strong> va fi trimisă la:
            </p>
            <p class="mb-5 px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-lg">
                {{ $clientEmail }}
            </p>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('invoices.send-email', $invoice) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Trimite
                    </button>
                </form>
                <button type="button"
                        onclick="document.getElementById('modal-send-email').style.display='none'"
                        class="flex-1 px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Anulează
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-6xl">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $invoice->number }}</h2>
                <p class="text-sm text-gray-500">{{ $invoice->client?->name ?? 'Client necunoscut' }}</p>
            </div>
            <div class="flex gap-2 flex-wrap justify-end">
                <a href="{{ route('invoices.edit', $invoice) }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Editează
                </a>
                <form method="POST" action="{{ route('invoices.duplicate', $invoice) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Duplică
                    </button>
                </form>
                <a href="{{ route('invoices.downloadPdf', $invoice) }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800">
                    Descarcă PDF
                </a>
                @if($clientEmail)
                    <button type="button"
                            onclick="document.getElementById('modal-send-email').style.display='flex'"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Trimite pe email
                    </button>
                @else
                    <button type="button" disabled title="Clientul nu are email setat"
                            class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        Trimite pe email
                    </button>
                @endif
                @if($invoice->status === 'draft')
                    <form method="POST" action="{{ route('invoices.markAsSent', $invoice) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Marchează ca trimisă
                        </button>
                    </form>
                @endif
                @if(!in_array($invoice->status, ['paid', 'cancelled']))
                    <button type="button"
                            onclick="document.getElementById('modal-paid').style.display='flex'"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Marchează încasată
                    </button>
                    <button type="button"
                            onclick="document.getElementById('modal-cancel').style.display='flex'"
                            class="px-4 py-2 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-50">
                        Anulează factura
                    </button>
                @endif
                <a href="{{ route('invoices.index') }}"
                   class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Înapoi
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">

            {{-- Status + date --}}
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div>
                    <p class="mb-1 text-xs text-gray-500">Status</p>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        {{ $invoice->status === 'paid'      ? 'bg-green-100 text-green-700'   : '' }}
                        {{ $invoice->status === 'draft'     ? 'bg-gray-100 text-gray-600'     : '' }}
                        {{ $invoice->status === 'sent'      ? 'bg-blue-100 text-blue-700'     : '' }}
                        {{ $invoice->status === 'overdue'   ? 'bg-red-100 text-red-700'       : '' }}
                        {{ $invoice->status === 'cancelled' ? 'bg-orange-100 text-orange-700' : '' }}">
                        {{ match($invoice->status) {
                            'paid'      => 'Încasată',
                            'draft'     => 'Draft',
                            'sent'      => 'Trimisă',
                            'overdue'   => 'Restantă',
                            'cancelled' => 'Anulată',
                            default     => $invoice->status
                        } }}
                    </span>
                </div>
                <div>
                    <p class="mb-1 text-xs text-gray-500">Data emiterii</p>
                    <p class="text-sm font-medium text-gray-900">{{ $invoice->issue_date->format('d.m.Y') }}</p>
                </div>
                <div>
                    <p class="mb-1 text-xs text-gray-500">Scadență</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="mb-1 text-xs text-gray-500">Monedă</p>
                    <p class="text-sm font-medium text-gray-900">{{ $invoice->currency }}</p>
                </div>
                @if($invoice->reminder_sent_at)
                <div>
                    <p class="mb-1 text-xs text-gray-500">Reminder trimis</p>
                    <p class="text-sm font-medium text-amber-600">{{ $invoice->reminder_sent_at->format('d.m.Y H:i') }}</p>
                </div>
                @endif
            </div>

            {{-- Linii factura --}}
            <table class="w-full mb-6 text-sm">
                <thead class="border-gray-200 bg-gray-50 border-y">
                    <tr>
                        <th class="px-4 py-2 font-medium text-left text-gray-600">Descriere</th>
                        <th class="px-4 py-2 font-medium text-right text-gray-600">Cantitate</th>
                        <th class="px-4 py-2 font-medium text-right text-gray-600">Preț unitar</th>
                        <th class="px-4 py-2 font-medium text-right text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="px-4 py-3 text-gray-900">{{ $item->description }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">
                                {{ number_format($item->unit_price, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                            <td class="px-4 py-3 font-medium text-right text-gray-900">
                                {{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-200">
                        <td colspan="3" class="px-4 py-3 font-semibold text-right text-gray-700">Subtotal:</td>
                        <td class="px-4 py-3 font-semibold text-right text-gray-900">
                            {{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}
                        </td>
                    </tr>
                    @if($invoice->vat_rate)
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right text-gray-600">
                                TVA ({{ (int)$invoice->vat_rate }}%):
                            </td>
                            <td class="px-4 py-2 text-right text-gray-600">
                                {{ number_format($invoice->vat_amount, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                        </tr>
                        <tr class="border-t border-gray-200">
                            <td colspan="3" class="px-4 py-3 font-semibold text-right text-gray-700">Total cu TVA:</td>
                            <td class="px-4 py-3 text-xl font-bold text-right text-gray-900">
                                {{ number_format($invoice->total_with_vat, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                        </tr>
                    @else
                        <tr class="border-t border-gray-200">
                            <td colspan="3" class="px-4 py-3 font-semibold text-right text-gray-700">Total factură:</td>
                            <td class="px-4 py-3 text-xl font-bold text-right text-gray-900">
                                {{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                        </tr>
                    @endif
                </tfoot>
            </table>

            @if($invoice->notes)
                <div class="pt-4 border-t border-gray-100">
                    <p class="mb-1 text-xs text-gray-500">Note</p>
                    <p class="text-sm text-gray-700">{{ $invoice->notes }}</p>
                </div>
            @endif

        </div>
    </div>

    <script>
        document.querySelectorAll('[id^="modal-"]').forEach(function(modal) {
            modal.addEventListener('click', function(e) { if (e.target === modal) modal.style.display = 'none'; });
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.querySelectorAll('[id^="modal-"]').forEach(function(m) { m.style.display = 'none'; });
        });
    </script>

</x-cashly-layout>
