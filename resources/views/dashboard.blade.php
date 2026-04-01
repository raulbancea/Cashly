<x-cashly-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Venituri luna aceasta</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($revenue, 2, ',', '.') }} RON
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Cheltuieli luna aceasta</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ number_format($expenses, 2, ',', '.') }} RON
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Profit net</p>
            <p class="text-2xl font-bold mt-1 {{ $profit >= 0 ? 'text-teal-600' : 'text-red-500' }}">
                {{ number_format($profit, 2, ',', '.') }} RON
            </p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl">
            <p class="text-sm text-gray-500">Facturi restante</p>
            <p class="text-2xl font-bold mt-1 {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-900' }}">
                {{ $overdueCount }}
            </p>
        </div>
    </div>

    {{-- Grafic cash flow --}}
    <div class="p-5 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-sm font-semibold text-gray-700">Cash Flow — ultimele 6 luni</h3>
        <canvas id="cashFlowChart" height="100"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const cashFlow = @json($cashFlow);

        const labels = cashFlow.map(item => item.month);
        const revenues = cashFlow.map(item => item.revenue);
        const expenses = cashFlow.map(item => item.expenses);

        new Chart(document.getElementById('cashFlowChart'), {
            type: 'bar',
            data: {
                labels: labels,
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
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value.toLocaleString('ro-RO') + ' RON'
                        }
                    }
                }
            }
        });
    </script>

</x-cashly-layout>
