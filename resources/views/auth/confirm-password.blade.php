{{-- Pagina de confirmare parola pentru actiuni sensibile --}}
<x-guest-layout>
    {{-- Mesaj explicativ pentru utilizator --}}
    <div class="mb-4 text-sm text-gray-600">
        Aceasta este o zona securizata a aplicatiei. Te rugam sa confirmi parola inainte de a continua.
    </div>

    {{-- Formular de confirmare parola --}}
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- Campul pentru parola --}}
        <div>
            <x-input-label for="password" :value="'Parolă'" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Buton de confirmare --}}
        <div class="flex justify-end mt-4">
            <x-primary-button>
                Confirmă
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
