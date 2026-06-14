@php use Illuminate\Support\Facades\Storage; @endphp
<x-cashly-layout>
    <x-slot name="title">Setări</x-slot>

    <div class="max-w-3xl">

        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Setări</h2>
            <p class="text-sm text-gray-500">Gestionează contul, firma și preferințele tale</p>
        </div>

        <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
            <button onclick="switchTab('profil')" id="tab-profil"
                    class="px-5 py-3 text-sm font-medium border-b-2 -mb-px transition-colors">
                Profil
            </button>
            <button onclick="switchTab('firma')" id="tab-firma"
                    class="px-5 py-3 text-sm font-medium border-b-2 -mb-px transition-colors">
                Firmă &amp; Factură
            </button>
            <button onclick="switchTab('categorii')" id="tab-categorii"
                    class="px-5 py-3 text-sm font-medium border-b-2 -mb-px transition-colors">
                Categorii cheltuieli
            </button>
        </div>

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="p-3 mb-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="panel-profil" class="tab-panel space-y-5">

                <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                    <h3 class="mb-1 text-sm font-semibold text-gray-800">Fotografie de profil</h3>
                    <p class="mb-4 text-xs text-gray-400">Avatarul tău apare în bara laterală și pe pagina de clienți.</p>
                    <div class="flex items-center gap-5">
                        <div style="width:72px;height:72px;min-width:72px;border-radius:50%;overflow:hidden;flex-shrink:0;border:2px solid #e5e7eb;">
                            @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                <img src="{{ Storage::disk('public')->url($user->avatar) }}"
                                     style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#0d9488;color:#fff;font-size:1.75rem;font-weight:700;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('profile.edit') }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 inline-block">
                                Schimbă fotografia
                            </a>
                            <p class="mt-2 text-xs text-gray-400">JPG, PNG sau WebP, max 2 MB</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-gray-800">Informații personale</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Nume complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Email</label>
                            <div class="flex items-center px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg text-gray-400">
                                {{ $user->email }}
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Email-ul nu poate fi modificat.</p>
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Telefon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   placeholder="+40 700 000 000"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2 pb-8">
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                </div>
            </div>

            <div id="panel-firma" class="tab-panel space-y-5" style="display:none;">

                <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-gray-800">Date firmă</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Nume firmă</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                                   placeholder="Ex: Firma Mea SRL"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">CUI / CIF</label>
                            <input type="text" name="company_vat" value="{{ old('company_vat', $user->company_vat) }}"
                                   placeholder="RO12345678"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Monedă implicită</label>
                            <select name="currency"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="RON" {{ old('currency', $user->currency) === 'RON' ? 'selected' : '' }}>🇷🇴 RON - Leu românesc</option>
                                <option value="EUR" {{ old('currency', $user->currency) === 'EUR' ? 'selected' : '' }}>🇪🇺 EUR - Euro</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Adresă</label>
                            <textarea name="address" rows="2"
                                      placeholder="Str. Exemplu nr. 1, București"
                                      class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                    <h3 class="mb-1 text-sm font-semibold text-gray-800">Date bancare</h3>
                    <p class="mb-4 text-xs text-gray-400">Apar pe facturile PDF generate.</p>

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Cont bancar (IBAN)</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}"
                               placeholder="RO49AAAA1B31007593840000"
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 font-mono tracking-wide">
                    </div>
                </div>

                <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                    <h3 class="mb-1 text-sm font-semibold text-gray-800">Logo firmă</h3>
                    <p class="mb-4 text-xs text-gray-400">Apare în colțul stânga al facturii PDF. Format PNG sau JPG, max 2 MB.</p>

                    @if($user->logo && Storage::disk('public')->exists($user->logo))
                        <div class="flex items-center gap-4 p-4 mb-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <img src="{{ Storage::url($user->logo) }}" alt="Logo" style="max-height:48px;max-width:160px;object-fit:contain;display:block;">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-600">Logo curent</p>
                                <p class="text-xs text-gray-400">Încarcă un fișier nou pentru a-l înlocui</p>
                            </div>
                            <label class="flex items-center gap-2 text-xs text-red-500 cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300">
                                Șterge logo
                            </label>
                        </div>
                    @endif

                    <input type="file" name="logo" accept="image/png,image/jpeg"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                    @error('logo')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2 pb-8">
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                        Salvează
                    </button>
                </div>
            </div>

        </form>

        <div id="panel-categorii" class="tab-panel space-y-5" style="display:none;">

            <div id="cat-delete-modal" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;" aria-hidden="true">
                <div role="dialog" aria-modal="true" aria-label="Șterge categorie" style="background:#fff;border-radius:1rem;padding:1.5rem;width:100%;max-width:380px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                        <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                            <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-weight:600;color:#111827;font-size:0.95rem;">Șterge categorie</p>
                            <p style="font-size:0.75rem;color:#6b7280;" id="cat-modal-name"></p>
                        </div>
                    </div>
                    <p style="font-size:0.875rem;color:#374151;margin-bottom:1.25rem;line-height:1.5;">
                        Cheltuielile existente din această categorie nu vor fi șterse, dar vor rămâne fără categorie.
                    </p>
                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                        <button onclick="closeCatModal()"
                                style="padding:0.5rem 1.25rem;font-size:0.875rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;color:#374151;cursor:pointer;">
                            Anulează
                        </button>
                        <form id="cat-delete-form" method="POST" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="padding:0.5rem 1.25rem;font-size:0.875rem;border:none;border-radius:0.5rem;background:#ef4444;color:#fff;cursor:pointer;font-weight:500;">
                                Șterge
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                <h3 class="mb-1 text-sm font-semibold text-gray-800">Categorii cheltuieli</h3>
                <p class="mb-4 text-xs text-gray-400">Folosite pentru a clasifica cheltuielile tale.</p>

                @if($categories->isEmpty())
                    <p class="py-6 text-sm text-center text-gray-400">Nu ai nicio categorie încă.</p>
                @else
                    <div class="space-y-2 mb-5">
                        @foreach($categories as $category)
                            <div class="flex items-center justify-between px-4 py-3 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div style="width:12px;height:12px;min-width:12px;border-radius:50%;background-color:{{ $category->color }};"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                </div>
                                <button type="button"
                                        onclick="openCatModal('{{ route('settings.categories.destroy', $category) }}', {{ json_encode($category->name) }})"
                                        class="text-xs text-red-400 hover:text-red-600 font-medium">
                                    Șterge
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-100">
                    <p class="mb-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Adaugă categorie nouă</p>
                    <form method="POST" action="{{ route('settings.categories.store') }}" class="flex flex-wrap gap-3">
                        @csrf
                        <input type="text" name="name" placeholder="Nume categorie" required
                               class="flex-1 px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500">Culoare</label>
                            <input type="color" name="color" value="#6366f1"
                                   style="width:40px;height:40px;border:1px solid #d1d5db;border-radius:8px;cursor:pointer;padding:2px;">
                        </div>
                        <button type="submit"
                                class="px-4 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                            Adaugă
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        const tabs = ['profil', 'firma', 'categorii'];

        const activeClass = ['border-teal-600', 'text-teal-600'];
        const inactiveClass = ['border-transparent', 'text-gray-500', 'hover:text-gray-700'];

        function switchTab(name) {
            tabs.forEach(t => {
                const btn = document.getElementById('tab-' + t);
                const panel = document.getElementById('panel-' + t);
                if (t === name) {
                    panel.style.display = '';
                    btn.classList.remove(...inactiveClass);
                    btn.classList.add(...activeClass);
                } else {
                    panel.style.display = 'none';
                    btn.classList.remove(...activeClass);
                    btn.classList.add(...inactiveClass);
                }
            });
            location.hash = name;
        }

        // Determine active tab: error fields, hash, or default
        @if($errors->has('name') || $errors->has('phone'))
            switchTab('profil');
        @elseif($errors->has('company_name') || $errors->has('company_vat') || $errors->has('address') || $errors->has('bank_account') || $errors->has('currency') || $errors->has('logo'))
            switchTab('firma');
        @else
            const hash = location.hash.replace('#', '');
            switchTab(tabs.includes(hash) ? hash : 'profil');
        @endif

        // Category modal
        const catModal = document.getElementById('cat-delete-modal');

        function openCatModal(action, name) {
            document.getElementById('cat-delete-form').action = action;
            document.getElementById('cat-modal-name').textContent = name;
            catModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeCatModal() {
            catModal.style.display = 'none';
            document.body.style.overflow = '';
        }

        catModal.addEventListener('click', e => { if (e.target === catModal) closeCatModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCatModal(); });
    </script>

</x-cashly-layout>
