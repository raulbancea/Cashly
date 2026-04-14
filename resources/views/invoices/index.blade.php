<x-cashly-layout>
    <x-slot name="title">Facturi</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Facturi</h2>
            <p class="text-sm text-gray-500">Gestionează și urmărește facturile</p>
        </div>
        <a href="{{ route('invoices.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            + Factură nouă
        </a>
    </div>

    @if(session('success'))
        <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    @if($invoices->isEmpty())
        <div class="p-10 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Nu ai nicio factură încă.</p>
            <a href="{{ route('invoices.create') }}"
               class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Creează prima factură
            </a>
        </div>
    @else
        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Număr</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Client</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Data</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Scadență</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Status</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-600">Total</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-600">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $invoice->number }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $invoice->client->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $invoice->issue_date->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $invoice->status === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $invoice->status === 'sent' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ match($invoice->status) {
                                        'paid' => 'Încasată',
                                        'draft' => 'Draft',
                                        'sent' => 'Trimisă',
                                        'overdue' => 'Restantă',
                                        default => $invoice->status
                                    } }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-right text-gray-900">
                                {{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                                        Vezi
                                    </a>
                                    <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                                          onsubmit="return confirm('Sigur vrei să ștergi această factură?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                                            Șterge
                                        </button>
                                    </form>
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

</x-cashly-layout>
