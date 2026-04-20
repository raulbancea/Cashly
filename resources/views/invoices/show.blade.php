<x-cashly-layout>
    <x-slot name="title">Factură {{ $invoice->number }}</x-slot>

    <div class="max-w-4xl">

       {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $invoice->number }}</h2>
                <p class="text-sm text-gray-500">{{ $invoice->client?->name ?? 'Client necunoscut' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('invoices.edit', $invoice) }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Editează
                </a>
                <a href="{{ route('invoices.downloadPdf', $invoice) }}"
                class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800">
                    Descarcă PDF
                </a>
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
                    <form method="POST" action="{{ route('invoices.markAsPaid', $invoice) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Marchează încasată
                        </button>
                    </form>
                    <form method="POST" action="{{ route('invoices.markAsCancelled', $invoice) }}"
                          onsubmit="return confirm('Sigur vrei să anulezi această factură?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-50">
                            Anulează factura
                        </button>
                    </form>
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

        <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">

            {{-- Status + date --}}
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div>
                    <p class="mb-1 text-xs text-gray-500">Status</p>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $invoice->status === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                        {{ $invoice->status === 'sent' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
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

</x-cashly-layout>
