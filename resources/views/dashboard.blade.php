<x-cashly-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- KPIs RON --}}
    <div class="grid grid-cols-1 gap-6 mb-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Venituri luna aceasta (RON)</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($revenue_ron, 2, ',', '.') }} RON
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Cheltuieli luna aceasta (RON)</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($expenses_ron, 2, ',', '.') }} RON
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Profit net (RON)</p>
            <p class="text-2xl font-bold mt-1 {{ $profit_ron >= 0 ? 'text-teal-600' : 'text-red-500' }}">
                {{ number_format($profit_ron, 2, ',', '.') }} RON
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Facturi restante</p>
            <p class="text-2xl font-bold mt-1 {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-900' }}">
                {{ $overdueCount }}
            </p>
        </div>
    </div>

    {{-- KPIs EUR (afișat doar dacă există date) --}}
    @if($revenue_eur > 0 || $expenses_eur > 0)
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 xl:grid-cols-3">
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Venituri luna aceasta (EUR)</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($revenue_eur, 2, ',', '.') }} EUR
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Cheltuieli luna aceasta (EUR)</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($expenses_eur, 2, ',', '.') }} EUR
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Profit net (EUR)</p>
            <p class="text-2xl font-bold mt-1 {{ $profit_eur >= 0 ? 'text-teal-600' : 'text-red-500' }}">
                {{ number_format($profit_eur, 2, ',', '.') }} EUR
            </p>
        </div>
    </div>
    @endif

    {{-- Grafic cash flow RON --}}
    <div class="p-5 mb-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-sm font-semibold text-gray-700">Cash Flow RON — ultimele 6 luni</h3>
        <canvas id="cashFlowChartRon" height="100"></canvas>
    </div>

    {{-- Grafic cash flow EUR (afișat doar dacă există date) --}}
    @php
        $hasEurCashFlow = collect($cashFlow_eur)->contains(fn($m) => $m['revenue'] > 0 || $m['expenses'] > 0);
    @endphp
    @if($hasEurCashFlow)
    <div class="p-5 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-sm font-semibold text-gray-700">Cash Flow EUR — ultimele 6 luni</h3>
        <canvas id="cashFlowChartEur" height="100"></canvas>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function buildChart(canvasId, cashFlow, currency) {
            const labels   = cashFlow.map(item => item.month);
            const revenues = cashFlow.map(item => item.revenue);
            const expenses = cashFlow.map(item => item.expenses);

            new Chart(document.getElementById(canvasId), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Venituri',
                            data: revenues,
                            backgroundColor: 'rgba(13, 148, 136, 0.7)',
                            borderRadius: 4,
                        },
                        {
                            label: 'Cheltuieli',
                            data: expenses,
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => value.toLocaleString('ro-RO') + ' ' + currency
                            }
                        }
                    }
                }
            });
        }

        buildChart('cashFlowChartRon', @json($cashFlow_ron), 'RON');

        @if($hasEurCashFlow)
        buildChart('cashFlowChartEur', @json($cashFlow_eur), 'EUR');
        @endif
    </script>

</x-cashly-layout>
