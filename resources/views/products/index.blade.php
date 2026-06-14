<x-cashly-layout>
    <x-slot name="title">Produse</x-slot>

    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Produse și Servicii</h2>
            <p class="text-sm text-gray-500">Gestionează catalogul de produse și servicii</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            + Adaugă produs
        </a>
    </div>

    <div id="delete-modal" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1rem;padding:1.5rem;width:100%;max-width:400px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-weight:600;color:#111827;font-size:0.95rem;">Șterge produs</p>
                    <p style="font-size:0.75rem;color:#6b7280;" id="modal-subtitle"></p>
                </div>
            </div>
            <p style="font-size:0.875rem;color:#374151;margin-bottom:1.25rem;line-height:1.5;" id="modal-message"></p>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button onclick="closeDeleteModal()"
                        style="padding:0.5rem 1.25rem;font-size:0.875rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;color:#374151;cursor:pointer;">
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

    @if($products->isEmpty())
        <div class="p-10 text-center bg-white border border-gray-100 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Nu ai niciun produs încă.</p>
            <a href="{{ route('products.create') }}"
               class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Adaugă primul produs
            </a>
        </div>
    @else
        <div class="overflow-x-auto bg-white border border-gray-100 rounded-xl shadow-sm">
            <table class="w-full min-w-[500px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Denumire</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Categorie</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Preț</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-2.5">
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                @if($product->description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($product->description, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $product->category ?? '-' }}
                            </td>
                            <td class="px-5 py-2.5 font-medium text-right text-gray-900">
                                {{ number_format($product->price, 2, ',', '.') }} {{ $product->currency }}
                            </td>
                            <td class="px-5 py-2.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                                        Editează
                                    </a>
                                    <button type="button"
                                            onclick="openDeleteModal('{{ route('products.destroy', $product) }}', {{ json_encode($product->name) }})"
                                            class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                                        Șterge
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif

    <script>
        const modal = document.getElementById('delete-modal');

        function openDeleteModal(action, name) {
            document.getElementById('delete-form').action = action;
            document.getElementById('modal-subtitle').textContent = name;
            document.getElementById('modal-message').textContent =
                'Ești sigur că vrei să ștergi produsul "' + name + '"? Această acțiune nu poate fi anulată.';
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
