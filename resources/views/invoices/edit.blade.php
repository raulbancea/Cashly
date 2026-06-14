<x-cashly-layout>
    <x-slot name="title">Editează Factură</x-slot>

    <div class="max-w-4xl">
        <div class="mb-4">
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

            <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-gray-800">Detalii factură</h3>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Număr factură</label>
                        <p class="w-full px-3 py-2 text-sm font-medium text-gray-700 border border-gray-100 rounded-lg bg-gray-50">{{ $invoice->number }}</p>
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

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Data emiterii <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="issue_date" name="issue_date"
                               value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Scadență <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="due_date" name="due_date"
                               value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                               min="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}"
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
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">TVA</label>
                        <select name="vat_rate" id="vat-rate"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">Fără TVA</option>
                            <option value="5"  {{ old('vat_rate', $invoice->vat_rate) == '5'  ? 'selected' : '' }}>5%</option>
                            <option value="11" {{ old('vat_rate', $invoice->vat_rate) == '11' ? 'selected' : '' }}>11%</option>
                            <option value="19" {{ old('vat_rate', $invoice->vat_rate) == '19' ? 'selected' : '' }}>19%</option>
                            <option value="21" {{ old('vat_rate', $invoice->vat_rate) == '21' ? 'selected' : '' }}>21%</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-gray-800">Produse / Servicii</h3>

                <div id="currency-mismatch-warning" class="hidden mb-3 p-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg">
                    ⚠️ Produsul selectat este în <strong id="product-currency-label"></strong>, dar factura este în <strong id="invoice-currency-label"></strong>. Verifică prețul înainte de salvare.
                </div>

                <div class="overflow-x-auto -mx-5 px-5">
                <div id="items-container" style="min-width:460px;">
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
                            <select name="items[{{ $index }}][product_id]"
                                    class="w-full px-3 py-1 mb-1 text-xs text-gray-500 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-1 focus:ring-teal-400 item-product"
                                    onchange="fillFromProduct(this)">
                                <option value="">Selectează produs din catalog</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="items[{{ $index }}][description]"
                                   value="{{ old("items.$index.description", $item->description) }}"
                                   placeholder="Descriere serviciu/produs"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 item-description">
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

                </div>
                </div>

                <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-100">
                    <button type="button" onclick="addItem()"
                            class="text-sm font-medium text-teal-600 hover:text-teal-700">
                        + Adaugă linie
                    </button>
                    <div class="text-right space-y-1">
                        <div class="text-sm text-gray-500">
                            Subtotal: <span id="grand-total" class="font-semibold text-gray-900">{{ number_format($invoice->total, 2, ',', '.') }}</span>
                        </div>
                        <div id="vat-row" class="text-sm text-gray-500 {{ $invoice->vat_rate ? '' : 'hidden' }}">
                            TVA (<span id="vat-rate-label">{{ $invoice->vat_rate ? (int)$invoice->vat_rate : '' }}</span>%):
                            <span id="vat-amount-display" class="font-semibold text-gray-900">{{ number_format($invoice->vat_amount, 2, ',', '.') }}</span>
                        </div>
                        <div id="total-with-vat-row" class="{{ $invoice->vat_rate ? '' : 'hidden' }}">
                            <span class="text-sm text-gray-500">Total cu TVA:</span>
                            <span id="total-with-vat-display" class="ml-1 text-xl font-bold text-gray-900">{{ number_format($invoice->total_with_vat, 2, ',', '.') }}</span>
                        </div>
                        <div id="total-no-vat-row" class="{{ $invoice->vat_rate ? 'hidden' : '' }}">
                            <span class="text-sm text-gray-500">Total factură:</span>
                            <span id="total-no-vat-display" class="ml-1 text-xl font-bold text-gray-900">{{ number_format($invoice->total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
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
        const products = @json($products->keyBy('id'));
        let itemCount = {{ $invoice->items->count() }};

        function buildProductOptions() {
            let opts = '<option value="">Selectează produs din catalog</option>';
            Object.values(products).forEach(p => {
                opts += `<option value="${p.id}">${p.name}</option>`;
            });
            return opts;
        }

        function fillFromProduct(select) {
            const row = select.closest('.item-row');
            const product = products[select.value];
            if (!product) {
                checkCurrencyMismatch();
                return;
            }

            row.querySelector('.item-description').value = product.name;
            row.querySelector('.item-price').value = parseFloat(product.price);
            row.querySelector('.item-quantity').dispatchEvent(new Event('input'));
            checkCurrencyMismatch();
        }

        function checkCurrencyMismatch() {
            const invoiceCurrency = document.querySelector('select[name="currency"]').value;
            let mismatchedCurrency = null;
            document.querySelectorAll('.item-product').forEach(sel => {
                const p = products[sel.value];
                if (p && p.currency && p.currency !== invoiceCurrency) {
                    mismatchedCurrency = p.currency;
                }
            });
            const warning = document.getElementById('currency-mismatch-warning');
            if (mismatchedCurrency) {
                document.getElementById('product-currency-label').textContent = mismatchedCurrency;
                document.getElementById('invoice-currency-label').textContent = invoiceCurrency;
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        function addItem() {
            const container = document.getElementById('items-container');
            const index = itemCount++;

            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-12 gap-2 mb-2';
            row.id = 'item-' + index;
            row.innerHTML = `
                <div class="col-span-5">
                    <select name="items[${index}][product_id]"
                            class="w-full px-3 py-1 mb-1 text-xs text-gray-500 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-1 focus:ring-teal-400 item-product"
                            onchange="fillFromProduct(this)">
                        ${buildProductOptions()}
                    </select>
                    <input type="text" name="items[${index}][description]"
                           placeholder="Descriere serviciu/produs"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 item-description">
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
            if (rows.length === 1) {
                btn.title = 'Trebuie cel puțin un articol';
                btn.style.color = '#ef4444';
                setTimeout(() => { btn.style.color = ''; btn.title = ''; }, 1500);
                return;
            }
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
            let subtotal = 0;
            document.querySelectorAll('.item-total').forEach(input => {
                subtotal += parseFloat(input.value.replace(',', '.')) || 0;
            });

            const vatSelect = document.getElementById('vat-rate');
            const vatRate = parseFloat(vatSelect.value) || 0;
            const vatAmount = vatRate ? Math.round(subtotal * vatRate / 100 * 100) / 100 : 0;
            const totalWithVat = Math.round((subtotal + vatAmount) * 100) / 100;

            document.getElementById('grand-total').textContent = subtotal.toFixed(2).replace('.', ',');

            if (vatRate > 0) {
                document.getElementById('vat-rate-label').textContent = vatRate;
                document.getElementById('vat-amount-display').textContent = vatAmount.toFixed(2).replace('.', ',');
                document.getElementById('total-with-vat-display').textContent = totalWithVat.toFixed(2).replace('.', ',');
                document.getElementById('vat-row').classList.remove('hidden');
                document.getElementById('total-with-vat-row').classList.remove('hidden');
                document.getElementById('total-no-vat-row').classList.add('hidden');
            } else {
                document.getElementById('total-no-vat-display').textContent = subtotal.toFixed(2).replace('.', ',');
                document.getElementById('vat-row').classList.add('hidden');
                document.getElementById('total-with-vat-row').classList.add('hidden');
                document.getElementById('total-no-vat-row').classList.remove('hidden');
            }
        }

        document.getElementById('vat-rate').addEventListener('change', updateGrandTotal);
        document.querySelector('select[name="currency"]').addEventListener('change', checkCurrencyMismatch);
        document.querySelectorAll('.item-row').forEach(row => attachListeners(row));

        document.getElementById('issue_date').addEventListener('change', function () {
            const dueDateInput = document.getElementById('due_date');
            dueDateInput.min = this.value;
            if (dueDateInput.value && dueDateInput.value < this.value) {
                dueDateInput.value = this.value;
            }
        });
    </script>

</x-cashly-layout>
