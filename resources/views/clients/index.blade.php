<x-cashly-layout>
    <x-slot name="title">Clienți</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Clienți</h2>
            <p class="text-sm text-gray-500">{{ $clients->total() }} {{ $clients->total() === 1 ? 'client' : 'clienți' }} în portofoliu</p>
        </div>
        <a href="{{ route('clients.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adaugă client
        </a>
    </div>

    {{-- Modal confirmare ștergere --}}
    <div id="delete-modal" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1rem;padding:1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-weight:600;color:#111827;font-size:0.95rem;" id="modal-title">Șterge client</p>
                    <p style="font-size:0.75rem;color:#6b7280;" id="modal-subtitle"></p>
                </div>
            </div>
            <p style="font-size:0.875rem;color:#374151;margin-bottom:1.25rem;line-height:1.5;" id="modal-message"></p>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button onclick="closeDeleteModal()"
                        style="px:1rem;padding:0.5rem 1.25rem;font-size:0.875rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;color:#374151;cursor:pointer;">
                    Anulează
                </button>
                <form id="delete-form" method="POST" style="margin:0;">
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

    @if($clients->isEmpty())
        <div style="padding:48px 24px;text-align:center;background:#fff;border:1px solid #f1f5f9;border-radius:1rem;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="width:56px;height:56px;background:#f0fdfa;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="26" height="26" fill="none" stroke="#0d9488" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p style="font-size:0.9375rem;font-weight:600;color:#0f172a;margin:0 0 6px;">Niciun client încă</p>
            <p style="font-size:0.8125rem;color:#94a3b8;margin:0 0 20px;">Adaugă primul client pentru a putea emite facturi și urmări plățile.</p>
            <a href="{{ route('clients.create') }}"
               style="display:inline-block;padding:9px 22px;font-size:0.875rem;font-weight:600;color:#fff;background:#0d9488;border-radius:0.5rem;text-decoration:none;">
                + Adaugă primul client
            </a>
        </div>
    @else
        @php
            $avatarColors = [
                '#0d9488', '#3b82f6', '#8b5cf6', '#ec4899',
                '#f97316', '#06b6d4', '#10b981', '#6366f1',
            ];
        @endphp

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach($clients as $i => $client)
                @php
                    $color = $avatarColors[$i % count($avatarColors)];
                    $statusStyle = match($client->status) {
                        'active'   => 'background:#dcfce7;color:#15803d',
                        'prospect' => 'background:#fef9c3;color:#a16207',
                        default    => 'background:#f3f4f6;color:#6b7280',
                    };
                    $statusLabel = match($client->status) {
                        'active'   => 'Activ',
                        'prospect' => 'Prospect',
                        default    => 'Inactiv',
                    };
                @endphp

                <div class="bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-150">

                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div style="width:40px;height:40px;min-width:40px;border-radius:50%;overflow:hidden;flex-shrink:0;">
                                @if($client->avatar)
                                    <img src="{{ Storage::disk('public')->url($client->avatar) }}"
                                         alt="{{ $client->name }}"
                                         style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background-color:{{ $color }};color:#fff;font-weight:600;font-size:0.875rem;">
                                        {{ strtoupper(mb_substr($client->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate text-sm">{{ $client->name }}</p>
                                @if($client->cui)
                                    <p class="text-xs text-gray-400">CUI: {{ $client->cui }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-md flex-shrink-0"
                                  style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                        </div>

                        <div class="space-y-1 pl-[52px]">
                            @if($client->email)
                                <p class="text-sm text-gray-500 truncate">
                                    <a href="mailto:{{ $client->email }}" class="hover:text-teal-600">{{ $client->email }}</a>
                                </p>
                            @endif
                            @if($client->phone)
                                <p class="text-sm text-gray-500">{{ $client->phone }}</p>
                            @endif
                            @if(!$client->email && !$client->phone)
                                <p class="text-xs text-gray-300 italic">Fără date de contact</p>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between px-5 py-2.5 border-t border-gray-50">
                        <span class="text-xs text-gray-400">
                            {{ $client->invoices_count }} {{ $client->invoices_count === 1 ? 'factură' : 'facturi' }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('clients.show', $client) }}"
                               class="px-3 py-1.5 text-xs font-medium text-teal-700 border border-teal-200 rounded-lg hover:bg-teal-50 transition-colors">
                                Vezi
                            </a>
                            <a href="{{ route('clients.edit', $client) }}"
                               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                Editează
                            </a>
                            <button type="button"
                                    onclick="openDeleteModal(
                                        '{{ route('clients.destroy', $client) }}',
                                        {{ json_encode($client->name) }},
                                        {{ $client->invoices_count }}
                                    )"
                                    class="px-2 py-1.5 text-xs text-red-400 border border-red-100 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                Șterge
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $clients->links() }}
        </div>
    @endif

    <script>
        const modal = document.getElementById('delete-modal');

        function openDeleteModal(action, name, invoiceCount) {
            document.getElementById('delete-form').action = action;
            document.getElementById('modal-title').textContent = 'Șterge client';
            document.getElementById('modal-subtitle').textContent = name;

            if (invoiceCount > 0) {
                document.getElementById('modal-message').textContent =
                    'ATENȚIE: Acest client are ' + invoiceCount + ' ' +
                    (invoiceCount === 1 ? 'factură asociată care va fi ștearsă permanent.' : 'facturi asociate care vor fi șterse permanent.') +
                    ' Această acțiune nu poate fi anulată.';
            } else {
                document.getElementById('modal-message').textContent =
                    'Ești sigur că vrei să ștergi clientul "' + name + '"? Această acțiune nu poate fi anulată.';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeDeleteModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>

</x-cashly-layout>
