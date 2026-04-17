<x-cashly-layout>
    <x-slot name="title">Rapoarte {{ $selectedYear }}</x-slot>

    {{-- ── Header + selector an ──────────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Rapoarte financiare</h2>
            <p class="text-sm text-gray-500">Sumar anual — {{ $selectedYear }}</p>
        </div>
        <form method="GET" action="{{ route('reports.index') }}">
            <select name="an" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                @foreach($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if(empty($totale) && empty($monthly))
        <div class="p-10 text-center bg-white border border-gray-200 rounded-xl shadow-sm">
            <p class="text-gray-400">Nu există date pentru anul {{ $selectedYear }}.</p>
        </div>
    @else

    {{-- ── KPI cards — grid-cols-2, compacte ─────────────────────────────── --}}
    @foreach($totale as $currency => $t)
    <div class="grid grid-cols-2 gap-3 mb-4">

        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Venituri {{ $currency }}</p>
            <p class="mt-1 text-xl font-bold text-gray-900">
                {{ number_format($t['venituri'], 2, ',', '.') }}
                <span class="text-sm font-normal text-gray-400">{{ $currency }}</span>
            </p>
            <p class="mt-0.5 text-xs text-gray-400">facturi încasate</p>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Cheltuieli {{ $currency }}</p>
            <p class="mt-1 text-xl font-bold text-gray-900">
                {{ number_format($t['cheltuieli'], 2, ',', '.') }}
                <span class="text-sm font-normal text-gray-400">{{ $currency }}</span>
            </p>
            <p class="mt-0.5 text-xs text-gray-400">total cheltuieli</p>
        </div>

        <div class="p-4 bg-white border border-{{ $t['profit'] >= 0 ? 'teal' : 'red' }}-100 rounded-xl shadow-sm">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Profit net {{ $currency }}</p>
            <p class="mt-1 text-xl font-bold {{ $t['profit'] >= 0 ? 'text-teal-600' : 'text-red-500' }}">
                {{ number_format($t['profit'], 2, ',', '.') }}
                <span class="text-sm font-normal {{ $t['profit'] >= 0 ? 'text-teal-400' : 'text-red-300' }}">{{ $currency }}</span>
            </p>
            <p class="mt-0.5 text-xs text-gray-400">venituri − cheltuieli</p>
        </div>

        <div class="p-4 bg-white border border-blue-100 rounded-xl shadow-sm">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">TVA colectat</p>
            <p class="mt-1 text-xl font-bold text-blue-700">
                {{ number_format($totalVatColectat, 2, ',', '.') }}
                <span class="text-sm font-normal text-blue-400">{{ $currency }}</span>
            </p>
            <p class="mt-0.5 text-xs text-gray-400">din facturi plătite</p>
        </div>

    </div>
    @endforeach

    {{-- ── Grafic bar: venituri vs cheltuieli — full width, 300px ───────── --}}
    @foreach($monthly as $currency => $data)
    <div class="p-6 mb-4 bg-white border border-gray-200 rounded-xl shadow-sm">
        <h3 class="mb-4 text-sm font-semibold text-gray-700">
            Venituri vs Cheltuieli — {{ $selectedYear }}
            <span class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded">{{ $currency }}</span>
        </h3>
        <div style="height: 300px; position: relative;">
            <canvas id="chartMonthly{{ $currency }}"></canvas>
        </div>
    </div>
    @endforeach

    {{-- ── TVA colectat per cotă ────────────────────────────────────────── --}}
    @if($vatByRate->isNotEmpty())
    <div class="p-6 mb-4 bg-white border border-gray-200 rounded-xl shadow-sm">
        <h3 class="mb-4 text-sm font-semibold text-gray-700">TVA colectat pe cotă — {{ $selectedYear }}</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs font-medium tracking-wide text-left text-gray-400 uppercase border-b border-gray-100">
                    <th class="pb-3">Cotă TVA</th>
                    <th class="pb-3 text-right">Nr. facturi</th>
                    <th class="pb-3 text-right">TVA colectat</th>
                    <th class="pb-3 text-right">Pondere</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($vatByRate as $row)
                <tr>
                    <td class="py-2.5 font-medium text-gray-900">
                        <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full">{{ $row['cota'] }}%</span>
                    </td>
                    <td class="py-2.5 text-right text-gray-600">{{ $row['numar_facturi'] }}</td>
                    <td class="py-2.5 font-semibold text-right text-gray-900">
                        {{ number_format($row['total_vat'], 2, ',', '.') }} RON
                    </td>
                    <td class="py-2.5 text-right text-gray-500">
                        @if($totalVatColectat > 0)
                            {{ number_format($row['total_vat'] / $totalVatColectat * 100, 1) }}%
                        @else —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-200">
                    <td class="pt-3 font-semibold text-gray-700">Total</td>
                    <td class="pt-3 font-semibold text-right text-gray-700">{{ $vatByRate->sum('numar_facturi') }}</td>
                    <td class="pt-3 font-bold text-right text-gray-900">
                        {{ number_format($totalVatColectat, 2, ',', '.') }} RON
                    </td>
                    <td class="pt-3 text-right text-gray-500">100%</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- ── Cheltuieli pe categorie: bare + doughnut side-by-side ──────────── --}}
    @php $pieCategories = $expensesByCategory->where('ron', '>', 0)->values(); @endphp
    @php $pieCategories ??= collect(); @endphp
    @if($expensesByCategory->isNotEmpty())
    <div class="grid grid-cols-1 gap-4 mb-4 lg:grid-cols-2">

        {{-- Tabel cu bare de progres --}}
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-gray-700">Cheltuieli pe categorie — {{ $selectedYear }}</h3>
            <div class="space-y-3">
                @php $maxRon = $expensesByCategory->max('ron') ?: 1; @endphp
                @foreach($expensesByCategory as $cat)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2.5 h-2.5 rounded-full flex-shrink-0"
                                  style="background-color: {{ $cat['color'] }}"></span>
                            <span class="text-sm text-gray-700">{{ $cat['name'] }}</span>
                        </div>
                        <div class="text-xs font-medium text-gray-700 text-right">
                            @if($cat['ron'] > 0)
                                {{ number_format($cat['ron'], 2, ',', '.') }} RON
                            @endif
                            @if($cat['ron'] > 0 && $cat['eur'] > 0)
                                <span class="text-gray-300 mx-0.5">·</span>
                            @endif
                            @if($cat['eur'] > 0)
                                {{ number_format($cat['eur'], 2, ',', '.') }} EUR
                            @endif
                        </div>
                    </div>
                    @if($cat['ron'] > 0)
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all"
                             style="width: {{ min(100, round($cat['ron'] / $maxRon * 100)) }}%; background-color: {{ $cat['color'] }}">
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Doughnut chart cu total în centru --}}
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col items-center">
            <h3 class="mb-4 text-sm font-semibold text-gray-700 self-start">Distribuție cheltuieli (RON)</h3>
            @if($pieCategories->isNotEmpty())
                {{-- Wrapper pătrat, max 300px, pentru center text --}}
                <div class="relative" style="width: 280px; height: 280px;">
                    <canvas id="chartPieExpenses"></canvas>
                    <div id="doughnut-center"
                         class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-lg font-bold text-gray-900" id="doughnut-total">—</span>
                        <span class="text-xs text-gray-400">RON total</span>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center flex-1">
                    <p class="text-sm text-gray-400">Nu există cheltuieli în RON pentru {{ $selectedYear }}.</p>
                </div>
            @endif
        </div>

    </div>
    @endif

    @endif {{-- end has data --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ── Plugin center text pentru doughnut ────────────────────────────
        const centerTextPlugin = {
            id: 'centerText',
            afterDraw(chart) {
                if (chart.config.type !== 'doughnut') return;
                const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                const el = document.getElementById('doughnut-total');
                if (el) el.textContent = total.toLocaleString('ro-RO', { minimumFractionDigits: 2 });
            }
        };
        Chart.register(centerTextPlugin);

        // ── Bar charts venituri vs cheltuieli ─────────────────────────────
        @foreach($monthly as $currency => $data)
        (function () {
            const data = @json($data);
            const ctx  = document.getElementById('chartMonthly{{ $currency }}');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.month),
                    datasets: [
                        {
                            label: 'Venituri',
                            data: data.map(d => d.revenue),
                            backgroundColor: 'rgba(13, 148, 136, 0.75)',
                            borderRadius: 4,
                            borderSkipped: false,
                        },
                        {
                            label: 'Cheltuieli',
                            data: data.map(d => d.expenses),
                            backgroundColor: 'rgba(239, 68, 68, 0.65)',
                            borderRadius: 4,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + ctx.dataset.label + ': ' +
                                    ctx.parsed.y.toLocaleString('ro-RO', { minimumFractionDigits: 2 }) +
                                    ' {{ $currency }}'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: {
                                callback: v => v.toLocaleString('ro-RO') + ' {{ $currency }}'
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        })();
        @endforeach

        // ── Doughnut cheltuieli pe categorie ──────────────────────────────
        @if($pieCategories->isNotEmpty())
        (function () {
            const cats = @json($pieCategories);
            new Chart(document.getElementById('chartPieExpenses'), {
                type: 'doughnut',
                data: {
                    labels: cats.map(c => c.name),
                    datasets: [{
                        data:            cats.map(c => c.ron),
                        backgroundColor: cats.map(c => c.color),
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' +
                                    ctx.parsed.toLocaleString('ro-RO', { minimumFractionDigits: 2 }) + ' RON'
                            }
                        }
                    }
                }
            });
        })();
        @endif
    </script>

</x-cashly-layout>
