<x-cashly-layout>
    <x-slot name="title">Clienți</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Clienți</h2>
            <p class="text-sm text-gray-500">Gestionează portofoliul de clienți</p>
        </div>
        <a href="{{ route('clients.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            + Adaugă client
        </a>
    </div>

    {{-- Mesaj succes --}}
    @if(session('success'))
        <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    {{-- Lista clienti --}}
    @if($clients->isEmpty())
        <div class="p-10 text-center bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Nu ai niciun client încă.</p>
            <a href="{{ route('clients.create') }}"
               class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Adaugă primul client
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($clients as $client)
                <div class="p-5 bg-white border border-gray-200 rounded-xl">

                    {{-- Header card --}}
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $client->name }}</h3>
                            @if($client->cui)
                                <p class="text-xs text-gray-500">CUI: {{ $client->cui }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $client->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $client->status === 'prospect' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $client->status === 'inactive' ? 'bg-gray-100 text-gray-600' : '' }}
                            {{ ucfirst($client->status) }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="mb-4 space-y-1">
                        @if($client->email)
                            <p class="text-sm text-gray-600">{{ $client->email }}</p>
                        @endif
                        @if($client->phone)
                            <p class="text-sm text-gray-600">{{ $client->phone }}</p>
                        @endif
                    </div>

                    {{-- Actiuni --}}
                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        <a href="{{ route('clients.edit', $client) }}"
                           class="flex-1 text-center px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                            Editează
                        </a>
                        <form method="POST" action="{{ route('clients.destroy', $client) }}"
                              onsubmit="return confirm('Sigur vrei să ștergi acest client?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                                Șterge
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    @endif

</x-cashly-layout>
