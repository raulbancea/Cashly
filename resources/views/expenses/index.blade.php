<x-cashly-layout>
    <x-slot name="title">Cheltuieli</x-slot>

    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Cheltuieli</h2>
            <p class="text-sm text-gray-500">Monitorizează și gestionează cheltuielile</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('expenses.exportCsv') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                Export Excel
            </a>
            <a href="{{ route('expenses.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                + Adaugă cheltuială
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filtre --}}
    <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap items-end gap-3 p-4 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-500">Categorie</label>
            <select name="category_id" class="form-select pl-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Toate categoriile</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
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
                @if(request()->hasAny(['category_id', 'an']))
                    <a href="{{ route('expenses.index') }}"
                       class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Resetează
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if($expenses->isEmpty())
        <div class="p-10 text-center bg-white border border-gray-100 rounded-xl shadow-sm">
            @if(request()->hasAny(['category_id', 'an']))
                <p class="text-sm text-gray-500">Nicio cheltuială găsită pentru filtrele selectate.</p>
                <a href="{{ route('expenses.index') }}"
                   class="inline-block px-4 py-2 mt-3 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Resetează filtrele
                </a>
            @else
                <p class="text-sm text-gray-500">Nu ai nicio cheltuială înregistrată.</p>
                <a href="{{ route('expenses.create') }}"
                   class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Adaugă prima cheltuială
                </a>
            @endif
        </div>
    @else
        <div class="overflow-hidden bg-white border border-gray-100 rounded-xl shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Descriere</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Categorie</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Data</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Sumă</th>
                        <th class="px-5 py-2.5 text-center text-xs font-medium text-gray-400 uppercase tracking-wide">Bon</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-2.5 text-gray-900">{{ $expense->description }}</td>
                            <td class="px-5 py-2.5">
                                @if($expense->category)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                                          style="background-color: {{ $expense->category->color }}22; color: {{ $expense->category->color }}">
                                        {{ $expense->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-gray-600">
                                {{ $expense->date->format('d.m.Y') }}
                            </td>
                            <td class="px-5 py-2.5 font-medium text-right text-red-600">
                                -{{ number_format($expense->amount, 2, ',', '.') }} {{ $expense->currency }}
                            </td>
                            <td class="px-5 py-2.5 text-center">
                                @if($expense->receipt_path)
                                    <a href="{{ route('expenses.downloadReceipt', $expense) }}"
                                       title="Descarcă bon"
                                       class="inline-flex items-center text-teal-600 hover:text-teal-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('expenses.edit', $expense) }}"
                                       class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                                        Editează
                                    </a>
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                          onsubmit="return confirm('Sigur vrei să ștergi această cheltuială?')">
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
            {{ $expenses->links() }}
        </div>
    @endif

</x-cashly-layout>
