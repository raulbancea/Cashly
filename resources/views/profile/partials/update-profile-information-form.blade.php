{{-- Sectiunea pentru actualizarea informatiilor de profil --}}
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informații profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Actualizează numele și adresa de email ale contului tău.
        </p>
    </header>

    {{-- Formular ascuns pentru retrimis email de verificare --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Formularul principal de actualizare profil --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Campul pentru nume --}}
        <div>
            <x-input-label for="name" :value="'Nume'" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Campul pentru email --}}
        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            {{-- Avertisment daca emailul nu este verificat --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Adresa ta de email nu este verificată.

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Click aici pentru a retrimite emailul de verificare.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Un nou link de verificare a fost trimis pe adresa ta de email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Buton salvare si mesaj de confirmare --}}
        <div class="flex items-center gap-4">
            <x-primary-button>Salvează</x-primary-button>

            @if (session('status') === 'profile-updated')
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
