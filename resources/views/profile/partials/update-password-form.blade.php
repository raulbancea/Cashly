{{-- Sectiunea pentru schimbarea parolei --}}
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Schimbă parola
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Foloseste o parola lunga si aleatoare pentru a-ti proteja contul.
        </p>
    </header>

    {{-- Formularul de schimbare a parolei --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Parola curenta --}}
        <div>
            <x-input-label for="update_password_current_password" :value="'Parola curentă'" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Parola noua --}}
        <div>
            <x-input-label for="update_password_password" :value="'Parolă nouă'" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Confirmare parola noua --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="'Confirmă parola nouă'" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Buton salvare si mesaj de confirmare --}}
        <div class="flex items-center gap-4">
            <x-primary-button>Salvează</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Salvat.</p>
            @endif
        </div>
    </form>
</section>
