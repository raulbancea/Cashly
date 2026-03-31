<x-cashly-layout>
    <x-slot name="title">Setări</x-slot>

    @if(session('success'))
        <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    {{-- Date cont --}}
    <div class="max-w-2xl mb-6">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Setări cont</h2>
            <p class="text-sm text-gray-500">Date profil și firmă</p>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Nume <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nume firmă</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">CUI / CIF</label>
                    <input type="text" name="company_vat" value="{{ old('company_vat', $user->company_vat) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Adresă</label>
                    <textarea name="address" rows="2"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Monedă implicită</label>
                    <select name="currency"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="RON" {{ old('currency', $user->currency) === 'RON' ? 'selected' : '' }}>RON</option>
                        <option value="EUR" {{ old('currency', $user->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>

                <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    Salvează setările
                </button>

            </form>
        </div>
    </div>

    {{-- Categorii cheltuieli --}}
    <div class="max-w-2xl">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Categorii cheltuieli</h2>
            <p class="text-sm text-gray-500">Gestionează categoriile pentru cheltuieli</p>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">

            {{-- Lista categorii --}}
            @if($categories->isEmpty())
                <p class="mb-4 text-sm text-gray-500">Nu ai nicio categorie încă.</p>
            @else
                <div class="mb-6 space-y-2">
                    @foreach($categories as $category)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full"
                                     style="background-color: {{ $category->color }}"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('settings.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Sigur vrei să ștergi această categorie?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-sm text-red-400 hover:text-red-600">
                                    Șterge
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Adauga categorie --}}
            <form method="POST" action="{{ route('settings.categories.store') }}">
                @csrf
                <div class="flex gap-3">
                    <input type="text" name="name" placeholder="Nume categorie"
                           class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <input type="color" name="color" value="#6366f1"
                           class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Adaugă
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-cashly-layout>
