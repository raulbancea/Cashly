{{-- Pagina pentru adaugarea unui produs sau serviciu nou --}}
<x-cashly-layout>
    <x-slot name="title">Adaugă Produs</x-slot>

    {{-- Titlu pagina --}}
    <div class="max-w-2xl">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Adaugă Produs / Serviciu</h2>
            <p class="text-sm text-gray-500">Completează detaliile produsului sau serviciului</p>
        </div>

        {{-- Formular adaugare produs --}}
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                {{-- Campul pentru denumire --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Denumire <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Categorie</label>
                    <input type="text" name="category" value="{{ old('category') }}"
                           placeholder="ex: Consultanță, Design, Development"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Preț <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                               @error('price') border-red-400 @enderror">
                        @error('price')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-32">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Monedă</label>
                        <select name="currency"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="RON" {{ old('currency') === 'RON' ? 'selected' : '' }}>RON</option>
                            <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Descriere</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                    <a href="{{ route('products.index') }}"
                       class="px-5 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Anulează
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-cashly-layout>
