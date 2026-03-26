<x-cashly-layout>
    <x-slot name="title">Editează Cheltuială</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Editează Cheltuială</h2>
            <p class="text-sm text-gray-500">{{ $expense->description }}</p>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <form method="POST" action="{{ route('expenses.update', $expense) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Descriere <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="description" value="{{ old('description', $expense->description) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('description') border-red-400 @enderror">
                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Sumă <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}"
                               step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                               @error('amount') border-red-400 @enderror">
                        @error('amount')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-32">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Monedă</label>
                        <select name="currency"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="RON" {{ old('currency', $expense->currency) === 'RON' ? 'selected' : '' }}>RON</option>
                            <option value="EUR" {{ old('currency', $expense->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Data <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('date') border-red-400 @enderror">
                    @error('date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Categorie</label>
                    <select name="category_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Fără categorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                    <a href="{{ route('expenses.index') }}"
                       class="px-5 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Anulează
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-cashly-layout>
