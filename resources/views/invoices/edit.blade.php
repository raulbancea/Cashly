<x-cashly-layout>
    <x-slot name="title">Editează Factură</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Editează Factura {{ $invoice->number }}</h2>
            <p class="text-sm text-gray-500">Modifică detaliile facturii</p>
        </div>

        <form method="POST" action="{{ route('invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="p-3 mb-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-6 mb-4 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 font-semibold text-gray-700">Detalii factură</h3>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Număr factură</label>
                        <input type="text" value="{{ $invoice->number }}" disabled
                               class="w-full px-3 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50">
                        <p class="mt-1 text-xs text-gray-400">Numărul facturii nu poate fi modificat</p>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Client <span class="text-red-500">*</span>
                        </label>
                        <select name="client_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                                @error('client_id') border-red-400 @enderror">
                            <option value="">Selectează client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Data emiterii <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="issue_date"
                               value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Scadență <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date"
                               value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Monedă</label>
                        <select name="currency"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="RON" {{ old('currency', $invoice->currency) == 'RON' ? 'selected' : '' }}>RON</option>
                            <option value="EUR" {{ old('currency', $invoice->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Linii factura --}}
            <div class="p-6 mb-4 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 font-semibold text-gray-700">Produse / Servicii</h3>

                <div id="items-container">
                    <div class="grid grid-cols-12 gap-2 mb-2 text-xs font-medium text-gray-500 uppercase">
                        <div class="col-span-5">Descriere</div>
                        <div class="col-span-2 text-right">Cantitate</div>
                        <div class="col-span-2 text-right">Preț unitar</div>
                        <div class="col-span-2 text-right">Total</div>
                        <div class="col-span-1"></div>
                    </div>

                    @foreach($invoice->items as $index => $item)
                    <div class="grid grid-cols-12 gap-2 mb-2 item-row" id="item-{{ $index }}">
                        <div class="col-span-5">
                            <input type="text" name="items[{{ $index }}][description]"
                                   value="{{ old("items.$index.description", $item->description) }}"
                                   placeholder="Descriere serviciu/produs"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="items[{{ $index }}][quantity]"
                                   value="{{ old("items.$index.quantity", $item->quantity) }}"
                                   step="0.01" min="0.01"
                                   class="w-full px-3 py-2 text-sm text-right border border-gray-300 rounded-lg item-quantity focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="items[{{ $index }}][unit_price]"
                                   value="{{ old("items.$index.unit_price", $item->unit_price) }}"
                                   step="0.01" min="0"
                                   class="w-full px-3 py-2 text-sm text-right border border-gray-300 rounded-lg item-price focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="col-span-2">
                            <input type="text" readonly
                                   class="w-full px-3 py-2 text-sm text-right border border-gray-200 rounded-lg item-total bg-gray-50"
                                   value="{{ number_format($item->total, 2, ',', '.') }}">
                        </div>
                        <div class="flex items-center justify-center col-span-1">
                            <button type="button" onclick="removeItem(this)"
                                    class="text-lg font-bold text-red-400 hover:text-red-600">×</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-100">
                    <button type="button" onclick="addItem()"
                            class="text-sm font-medium text-teal-600 hover:text-teal-700">
                        + Adaugă linie
                    </button>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Total factură:</span>
                        <span id="grand-total" class="ml-2 text-xl font-bold text-gray-900">
                            {{ number_format($invoice->total, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Note --}}
            <div class="p-6 mb-4 bg-white border border-gray-200 rounded-xl">
                <label class="block mb-1 text-sm font-medium text-gray-700">Note (opțional)</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                          placeholder="Informații suplimentare pentru client...">{{ old('notes', $invoice->notes) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Salvează modificările
                </button>
                <a href="{{ route('invoices.show', $invoice) }}"
                   class="px-5 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Anulează
                </a>
            </div>

        </form>
    </div>

    <script>
        let itemCount = {{ $invoice->items->count() }};

        function addItem() {
            const container = document.getElementById('items-container');
            const index = itemCount++;

            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-12 gap-2 mb-2';
            row.id = 'item-' + index;
            row.innerHTML = `
                <div class="col-span-5">
                    <input type="text" name="items[${index}][description]"
                           placeholder="Descriere serviciu/produs"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <input type="hidden" name="items[${index}][product_id]" value="">
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${index}][quantity]" value="1"
                           step="0.01" min="0.01"
                           class="w-full px-3 py-2 text-sm text-right border border-gray-300 rounded-lg item-quantity focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${index}][unit_price]" value="0"
                           step="0.01" min="0"
                           class="w-full px-3 py-2 text-sm text-right border border-gray-300 rounded-lg item-price focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="col-span-2">
                    <input type="text" readonly
                           class="w-full px-3 py-2 text-sm text-right border border-gray-200 rounded-lg item-total bg-gray-50"
                           value="0,00">
                </div>
                <div class="flex items-center justify-center col-span-1">
                    <button type="button" onclick="removeItem(this)"
                            class="text-lg font-bold text-red-400 hover:text-red-600">×</button>
                </div>
            `;

            container.appendChild(row);
            attachListeners(row);
        }

        function removeItem(btn) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length === 1) return;
            btn.closest('.item-row').remove();
            updateGrandTotal();
        }

        function attachListeners(row) {
            const qty = row.querySelector('.item-quantity');
            const price = row.querySelector('.item-price');
            const total = row.querySelector('.item-total');

            function calculate() {
                const q = parseFloat(qty.value) || 0;
                const p = parseFloat(price.value) || 0;
                const t = q * p;
                total.value = t.toFixed(2).replace('.', ',');
                updateGrandTotal();
            }

            qty.addEventListener('input', calculate);
            price.addEventListener('input', calculate);
        }

        function updateGrandTotal() {
            let grand = 0;
            document.querySelectorAll('.item-total').forEach(input => {
                grand += parseFloat(input.value.replace(',', '.')) || 0;
            });
            document.getElementById('grand-total').textContent = grand.toFixed(2).replace('.', ',');
        }

        document.querySelectorAll('.item-row').forEach(row => attachListeners(row));
    </script>

</x-cashly-layout>
