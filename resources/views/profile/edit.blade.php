@php use Illuminate\Support\Facades\Storage; @endphp
<x-cashly-layout>
    <x-slot name="title">Profil</x-slot>

    <div class="max-w-2xl space-y-6">

        <div>
            <h2 class="text-xl font-bold text-gray-900">Profilul meu</h2>
            <p class="text-sm text-gray-500">Gestionează informațiile contului tău</p>
        </div>

        {{-- ── Avatar ───────────────────────────────────────────────────── --}}
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="mb-1 text-sm font-semibold text-gray-800">Fotografie de profil</h3>
            <p class="mb-5 text-xs text-gray-400">JPG, PNG sau WebP, max 2 MB.</p>

            <div class="flex items-center gap-5">
                <div class="flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ Storage::disk('public')->url($user->avatar) }}"
                             alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-gray-100 shadow-sm">
                    @else
                        <div class="flex items-center justify-center w-20 h-20 text-2xl font-bold text-white bg-teal-500 rounded-full shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 space-y-3">
                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center gap-3">
                            <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp" required
                                   class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                          file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                            <button type="submit"
                                    class="px-4 py-1.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                                Încarcă
                            </button>
                        </div>
                        @error('avatar')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </form>

                    @if($user->avatar)
                        <form method="POST" action="{{ route('profile.avatar.remove') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">
                                Șterge fotografia
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Informații profil ─────────────────────────────────────────── --}}
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="mb-1 text-sm font-semibold text-gray-800">Informații cont</h3>
            <p class="mb-5 text-xs text-gray-400">Actualizează numele și adresa de email.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block mb-1 text-sm font-medium text-gray-700">
                        Nume <span class="text-red-500">*</span>
                    </label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name', $user->name) }}"
                           required autofocus autocomplete="name"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block mb-1 text-sm font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    @if($user->google_id)
                        <input id="email" type="email" name="email"
                               value="{{ $user->email }}"
                               readonly
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-400">Emailul este gestionat de contul tău Google.</p>
                    @else
                        <input id="email" type="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               required autocomplete="username"
                               class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                    @if(session('status') === 'profile-updated')
                        <span class="text-sm text-teal-600">Salvat!</span>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Schimbare parolă ─────────────────────────────────────────── --}}
        @if(!$user->google_id)
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h3 class="mb-1 text-sm font-semibold text-gray-800">Schimbă parola</h3>
            <p class="mb-5 text-xs text-gray-400">Folosește o parolă lungă și unică pentru securitate.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block mb-1 text-sm font-medium text-gray-700">
                        Parola curentă
                    </label>
                    <input id="current_password" type="password" name="current_password"
                           autocomplete="current-password"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->updatePassword->has('current_password') ? 'border-red-400' : 'border-gray-300' }}">
                    @if($errors->updatePassword->has('current_password'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">
                        Parolă nouă
                    </label>
                    <input id="password" type="password" name="password"
                           autocomplete="new-password"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->updatePassword->has('password') ? 'border-red-400' : 'border-gray-300' }}">
                    @if($errors->updatePassword->has('password'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">
                        Confirmă parola nouă
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           autocomplete="new-password"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Actualizează parola
                    </button>
                    @if(session('status') === 'password-updated')
                        <span class="text-sm text-teal-600">Parolă actualizată!</span>
                    @endif
                </div>
            </form>
        </div>
        @else
        <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm opacity-60">
            <h3 class="mb-1 text-sm font-semibold text-gray-800">Schimbă parola</h3>
            <p class="text-xs text-gray-400">Nu este disponibil pentru conturi conectate prin Google.</p>
        </div>
        @endif

        {{-- ── Ștergere cont ────────────────────────────────────────────── --}}
        <div class="p-6 bg-white border border-red-100 rounded-xl shadow-sm">
            <h3 class="mb-1 text-sm font-semibold text-red-700">Șterge contul</h3>
            <p class="mb-5 text-xs text-gray-400">
                Odată șters, contul și toate datele asociate vor fi eliminate permanent.
            </p>
            <button type="button"
                    onclick="document.getElementById('modal-delete-account').style.display='flex'"
                    class="px-5 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                Șterge contul
            </button>
        </div>

    </div>

    {{-- Modal confirmare ștergere cont --}}
    <div id="modal-delete-account"
         class="fixed inset-0 z-50 items-center justify-center bg-black/40"
         style="display: none;">
        <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg mx-4">
            <h3 class="mb-2 text-base font-semibold text-gray-900">Ești sigur că vrei să ștergi contul?</h3>
            <p class="mb-6 text-sm text-gray-500">
                Toate datele tale (facturi, cheltuieli, clienți) vor fi șterse permanent. Această acțiune nu poate fi anulată.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <label for="delete_password" class="block mb-1 text-sm font-medium text-gray-700">
                        Confirmă cu parola ta
                    </label>
                    <input id="delete_password" type="password" name="password"
                           placeholder="Parola ta"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->userDeletion->has('password') ? 'border-red-400' : 'border-gray-300' }}">
                    @if($errors->userDeletion->has('password'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                    @if($user->google_id)
                        <p class="mt-1 text-xs text-gray-400">Lasă gol dacă te-ai autentificat prin Google.</p>
                    @endif
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button"
                            onclick="document.getElementById('modal-delete-account').style.display='none'"
                            class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Anulează
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Șterge definitiv
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-cashly-layout>
