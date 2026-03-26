<x-cashly-layout>
    <x-slot name="title">Cheltuieli</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Cheltuieli</h2>
            <p class="text-sm text-gray-500">Monitorizează și gestionează cheltuielile</p>
        </div>
        <a href="{{ route('expenses.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            + Adaugă cheltuială
        </a>
    </div>

    @if(session('success'))
        <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    @if($expenses->isEmpty())
        <div class="p-10 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Nu ai nicio cheltuială înregistrată.</p>
            <a href="{{ route('expenses.create') }}"
               class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Adaugă prima cheltuială
            </a>
        </div>
    @else
        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Descriere</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Categorie</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-600">Data</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-600">Sumă</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-600">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-900">{{ $expense->description }}</td>
                            <td class="px-4 py-3">
                                @if($expense->category)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                                          style="background-color: {{ $expense->category->color }}22; color: {{ $expense->category->color }}">
                                        {{ $expense->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $expense->date->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-right text-red-600">
                                -{{ number_format($expense->amount, 2, ',', '.') }} {{ $expense->currency }}
                            </td>
                            <td class="px-4 py-3 text-right">
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
    @endif

</x-cashly-layout>
