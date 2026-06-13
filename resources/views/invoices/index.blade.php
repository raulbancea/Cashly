<x-cashly-layout>
    <x-slot name="title">Facturi</x-slot>

    {{-- Header pagina - pe mobil se impacheteaza pe doua randuri --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Facturi</h2>
            <p class="text-sm text-gray-500">Gestionează și urmărește facturile</p>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('invoices.exportCsv') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                Export Excel
            </a>
            <a href="{{ route('invoices.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                + Factură nouă
            </a>
        </div>
    </div>

    {{-- Modal confirmare ștergere --}}
    <div id="delete-modal" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1rem;padding:1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-weight:600;color:#111827;font-size:0.95rem;">Șterge factură</p>
                    <p style="font-size:0.75rem;color:#6b7280;" id="modal-subtitle"></p>
                </div>
            </div>
            <p style="font-size:0.875rem;color:#374151;margin-bottom:1.25rem;line-height:1.5;" id="modal-message"></p>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button onclick="closeDeleteModal()"
                        style="padding:0.5rem 1.25rem;font-size:0.875rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;color:#374151;cursor:pointer;">
                    Anulează
                </button>
                <form id="delete-form" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            style="padding:0.5rem 1.25rem;font-size:0.875rem;border:none;border-radius:0.5rem;background:#ef4444;color:#fff;cursor:pointer;font-weight:500;">
                        Șterge
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- Filtre --}}
    <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap items-end gap-3 p-4 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-500">Status</label>
            <select name="status" class="form-select pl-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Toate</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="sent"      {{ request('status') === 'sent'      ? 'selected' : '' }}>Trimise</option>
                <option value="paid"      {{ request('status') === 'paid'      ? 'selected' : '' }}>Încasate</option>
                <option value="overdue"   {{ request('status') === 'overdue'   ? 'selected' : '' }}>Restante</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Anulate</option>
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-500">Client</label>
            <select name="client_id" class="form-select pl-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Toți clienții</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-500">An</label>
            <select name="an" class="form-select pl-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Toți anii</option>
                @foreach($ani as $an)
                    <option value="{{ $an }}" {{ request('an') == $an ? 'selected' : '' }}>{{ $an }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <span class="text-xs font-medium text-gray-500 invisible">_</span>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-3 py-1.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Filtrează
                </button>
                @if(request()->hasAny(['status', 'client_id', 'an']))
                    <a href="{{ route('invoices.index') }}"
                       class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Resetează
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if($invoices->isEmpty())
        <div style="padding:48px 24px;text-align:center;background:#fff;border:1px solid #f1f5f9;border-radius:1rem;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="width:56px;height:56px;background:#f0fdfa;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="26" height="26" fill="none" stroke="#0d9488" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if(request()->hasAny(['status', 'client_id', 'an']))
                <p style="font-size:0.9375rem;font-weight:600;color:#0f172a;margin:0 0 6px;">Nicio factură găsită</p>
                <p style="font-size:0.8125rem;color:#94a3b8;margin:0 0 20px;">Niciun rezultat pentru filtrele selectate. Incearca alte criterii.</p>
                <a href="{{ route('invoices.index') }}"
                   style="display:inline-block;padding:8px 20px;font-size:0.875rem;color:#374151;border:1px solid #d1d5db;border-radius:0.5rem;text-decoration:none;background:#fff;">
                    Resetează filtrele
                </a>
            @else
                <p style="font-size:0.9375rem;font-weight:600;color:#0f172a;margin:0 0 6px;">Nicio factură inca</p>
                <p style="font-size:0.8125rem;color:#94a3b8;margin:0 0 20px;">Creează prima factură și trimite-o clientului în mai puțin de 2 minute.</p>
                <a href="{{ route('invoices.create') }}"
                   style="display:inline-block;padding:9px 22px;font-size:0.875rem;font-weight:600;color:#fff;background:#0d9488;border-radius:0.5rem;text-decoration:none;">
                    + Creează prima factură
                </a>
            @endif
        </div>
    @else
        {{-- MOBIL: tabel cu scroll orizontal ca sa nu se rupa layout-ul --}}
        <div class="overflow-x-auto bg-white border border-gray-100 rounded-xl shadow-sm">
            <table class="w-full min-w-[640px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Număr</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Client</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Data</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Scadență</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Total</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-2.5 font-medium text-gray-900">
                                {{ $invoice->number }}
                            </td>
                            <td class="px-5 py-2.5 text-gray-600">
                                {{ $invoice->client ? $invoice->client->name : '-' }}
                            </td>
                            <td class="px-5 py-2.5 text-gray-600">
                                {{ $invoice->issue_date->format('d.m.Y') }}
                            </td>
                            <td class="px-5 py-2.5 text-gray-600">
                                {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-5 py-2.5">
                                <div class="flex items-center gap-1.5">
                                    @php
                                        // Calculam eticheta statusului pentru afisare in tabel
                                        if ($invoice->status === 'paid') {
                                            $lblStatus = 'Încasată';
                                        } elseif ($invoice->status === 'draft') {
                                            $lblStatus = 'Draft';
                                        } elseif ($invoice->status === 'sent') {
                                            $lblStatus = 'Trimisă';
                                        } elseif ($invoice->status === 'overdue') {
                                            $lblStatus = 'Restantă';
                                        } elseif ($invoice->status === 'cancelled') {
                                            $lblStatus = 'Anulată';
                                        } else {
                                            $lblStatus = $invoice->status;
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $invoice->status === 'paid'      ? 'bg-green-100 text-green-700'   : '' }}
                                        {{ $invoice->status === 'draft'     ? 'bg-gray-100 text-gray-600'     : '' }}
                                        {{ $invoice->status === 'sent'      ? 'bg-blue-100 text-blue-700'     : '' }}
                                        {{ $invoice->status === 'overdue'   ? 'bg-red-100 text-red-700'       : '' }}
                                        {{ $invoice->status === 'cancelled' ? 'bg-orange-100 text-orange-700' : '' }}">
                                        {{ $lblStatus }}
                                    </span>
                                    @if($invoice->reminder_sent_at)
                                        <span title="Reminder trimis {{ $invoice->reminder_sent_at->format('d.m.Y') }}"
                                              class="text-amber-500 text-xs">●</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-2.5 font-medium text-right text-gray-900">
                                {{ number_format($invoice->effective_total, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                            <td class="px-5 py-2.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                                        Vezi
                                    </a>
                                    <button type="button"
                                            onclick="openDeleteModal('{{ route('invoices.destroy', $invoice) }}', {{ json_encode($invoice->number) }})"
                                            class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                                        Șterge
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    @endif

    <script>
        const modal = document.getElementById('delete-modal');

        function openDeleteModal(action, number) {
            document.getElementById('delete-form').action = action;
            document.getElementById('modal-subtitle').textContent = number;
            document.getElementById('modal-message').textContent =
                'Ești sigur că vrei să ștergi factura "' + number + '"? Această acțiune nu poate fi anulată.';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeDeleteModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>

</x-cashly-layout>
