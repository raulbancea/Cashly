<x-cashly-layout>
    <x-slot name="title">Adaugă Client</x-slot>

    <div class="max-w-2xl">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900">Adaugă Client Nou</h2>
            <p class="text-sm text-gray-500">Completează datele clientului</p>
        </div>

        <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="p-3 mb-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-gray-800">Fotografie / Logo client</h3>
                <div class="flex items-center gap-5">
                    <div id="avatar-preview"
                         style="width:64px;height:64px;min-width:64px;border-radius:50%;overflow:hidden;flex-shrink:0;background:#0d9488;display:flex;align-items:center;justify-content:center;">
                        <span id="avatar-initials" style="color:#fff;font-size:1.5rem;font-weight:700;">?</span>
                        <img id="avatar-img" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Încarcă imagine</label>
                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                               class="block text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        <p class="mt-1 text-xs text-gray-400">JPG, PNG sau WebP, max 2MB. Opțional.</p>
                        @error('avatar')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-gray-800">Date principale</h3>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Nume / Denumire firmă <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="client-name" value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">CUI / CNP</label>
                        <input type="text" name="cui" value="{{ old('cui') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="prospect" {{ old('status') === 'prospect' ? 'selected' : '' }}>Prospect</option>
                            <option value="active"   {{ old('status') === 'active'   ? 'selected' : '' }}>Activ</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactiv</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-5 mb-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-gray-800">Date de contact</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Telefon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Website</label>
                        <input type="url" name="website" value="{{ old('website') }}"
                               placeholder="https://exemplu.ro"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('website') border-red-400 @enderror">
                        @error('website') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Adresă</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

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

    <script>
        const nameInput = document.getElementById('client-name');
        const avatarInput = document.getElementById('avatar-input');
        const avatarInitials = document.getElementById('avatar-initials');
        const avatarImg = document.getElementById('avatar-img');
        const avatarPreview = document.getElementById('avatar-preview');

        nameInput.addEventListener('input', function () {
            if (avatarImg.style.display !== 'none') return;
            const letter = this.value.trim().charAt(0).toUpperCase() || '?';
            avatarInitials.textContent = letter;
        });

        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarImg.src = e.target.result;
                avatarImg.style.display = 'block';
                avatarInitials.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    </script>

</x-cashly-layout>
