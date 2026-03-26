<x-cashly-layout>
    <x-slot name="title">Editează Produs</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Editează Produs</h2>
            <p class="text-sm text-gray-500">{{ $product->name }}</p>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <form method="POST" action="{{ route('products.update', $product) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Denumire <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Categorie</label>
                    <input type="text" name="category" value="{{ old('category', $product->category) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Preț <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}"
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
                            <option value="RON" {{ old('currency', $product->currency) === 'RON' ? 'selected' : '' }}>RON</option>
                            <option value="EUR" {{ old('currency', $product->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Descriere</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description', $product->description) }}</textarea>
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
