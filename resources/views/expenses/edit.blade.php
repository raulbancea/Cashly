<x-cashly-layout>
    <x-slot name="title">Editează Cheltuială</x-slot>

    <div class="max-w-2xl">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Editează Cheltuială</h2>
            <p class="text-sm text-gray-500">{{ $expense->description }}</p>
        </div>

        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
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
                               step="0.01" min="0.01"
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
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-sm font-medium text-gray-700">Categorie</label>
                        <button type="button" onclick="document.getElementById('modal-categorie').classList.remove('hidden')"
                                class="text-xs font-medium text-teal-600 hover:text-teal-700">
                            + Categorie nouă
                        </button>
                    </div>
                    <select name="category_id" id="category-select"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Fără categorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (old('category_id', $expense->category_id) == $category->id || request('new_category_id') == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Bon / Chitanță</label>
                    @if($expense->receipt_path)
                        <div class="flex items-center gap-3 p-3 mb-2 border border-gray-200 rounded-lg bg-gray-50">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <a href="{{ route('expenses.downloadReceipt', $expense) }}"
                               class="text-sm text-teal-600 hover:underline flex-1 truncate">
                                Bon atașat, descarcă
                            </a>
                            <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                                <input type="checkbox" name="remove_receipt" value="1" class="rounded">
                                Șterge bonul
                            </label>
                        </div>
                    @endif
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                  file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    <p class="mt-1 text-xs text-gray-400">JPG, PNG sau PDF, max 5 MB</p>
                    @error('receipt')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
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

    {{-- Modal categorie nouă --}}
    <div id="modal-categorie" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
        <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-gray-900">Categorie nouă</h3>

            <form method="POST" action="{{ route('settings.categories.store') }}">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ request()->url() }}">

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Nume <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required autofocus
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="ex: Transport, Software...">
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Culoare</label>
                    <input type="color" name="color" value="#14b8a6"
                           class="w-10 h-10 p-1 border border-gray-300 rounded-lg cursor-pointer">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                    <button type="button" onclick="document.getElementById('modal-categorie').classList.add('hidden')"
                            class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Anulează
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-cashly-layout>
