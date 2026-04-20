<x-cashly-layout>
    <x-slot name="title">Produse</x-slot>

    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Produse și Servicii</h2>
            <p class="text-sm text-gray-500">Gestionează catalogul de produse și servicii</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            + Adaugă produs
        </a>
    </div>

    @if(session('success'))
        <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="p-10 text-center bg-white border border-gray-100 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">Nu ai niciun produs încă.</p>
            <a href="{{ route('products.create') }}"
               class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Adaugă primul produs
            </a>
        </div>
    @else
        <div class="overflow-hidden bg-white border border-gray-100 rounded-xl shadow-sm">
            <table class="w-full text-sm">
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
                                    <form method="POST" action="{{ route('products.destroy', $product) }}"
                                          onsubmit="return confirm('Sigur vrei să ștergi acest produs?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                                            Șterge
                                        </button>
                                    </form>
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

</x-cashly-layout>
