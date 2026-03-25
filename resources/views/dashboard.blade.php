<x-cashly-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

        {{-- KPI 1 --}}
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Venituri luna aceasta</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">0,00 RON</p>
        </div>

        {{-- KPI 2 --}}
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Cheltuieli luna aceasta</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">0,00 RON</p>
        </div>

        {{-- KPI 3 --}}
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Profit net</p>
            <p class="mt-1 text-2xl font-bold text-teal-600">0,00 RON</p>
        </div>

        {{-- KPI 4 --}}
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Facturi restante</p>
            <p class="mt-1 text-2xl font-bold text-red-500">0</p>
        </div>

    </div>

    <div class="p-5 mt-6 bg-white border border-gray-200 rounded-xl">
        <p class="text-sm font-medium text-gray-700">Bun venit în Cashly 👋</p>
        <p class="mt-1 text-sm text-gray-500">Începe prin a adăuga primul tău client.</p>
        <a href="{{ route('clients.index') }}"
           class="inline-block px-4 py-2 mt-3 text-sm text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            Adaugă client
        </a>
    </div>

</x-cashly-layout>
