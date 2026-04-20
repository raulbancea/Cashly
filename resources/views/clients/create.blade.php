<x-cashly-layout>
    <x-slot name="title">Adaugă Client</x-slot>

    <div class="max-w-2xl">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Adaugă Client Nou</h2>
            <p class="text-sm text-gray-500">Completează datele clientului</p>
        </div>

        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <form method="POST" action="{{ route('clients.store') }}">
                @csrf

                {{-- Nume --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Nume / Denumire firmă <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CUI --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">CUI / CNP</label>
                    <input type="text" name="cui" value="{{ old('cui') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                           @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telefon --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                {{-- Adresa --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Adresă</label>
                    <textarea name="address" rows="2"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('address') }}</textarea>
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="prospect" {{ old('status') === 'prospect' ? 'selected' : '' }}>Prospect</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Activ</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactiv</option>
                    </select>
                </div>

                {{-- Butoane --}}
                <div class="flex gap-3">
                    <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                    <a href="{{ route('clients.index') }}"
                       class="px-5 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Anulează
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-cashly-layout>
