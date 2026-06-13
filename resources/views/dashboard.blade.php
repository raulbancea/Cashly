<x-cashly-layout>
<x-slot name="title">Dashboard</x-slot>

@if($overdueCount > 0)
<div class="flex items-center gap-3 px-4 py-3 mb-4 border border-red-200 bg-red-50 rounded-xl">
    <svg class="flex-shrink-0 w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <p class="text-sm text-red-700">
        <span class="font-semibold">Atenție:</span>
        ai <span class="font-semibold">{{ $overdueCount }} {{ $overdueCount === 1 ? 'factură restantă' : 'facturi restante' }}</span> neîncasate.
    </p>
    <a href="{{ route('invoices.index', ['status' => 'overdue']) }}"
       class="flex-shrink-0 px-3 py-1 ml-auto text-xs font-medium text-red-700 border border-red-300 rounded-lg hover:bg-red-100 whitespace-nowrap">
        Vezi facturile
    </a>
</div>
@endif

<div class="grid grid-cols-2 gap-4 mb-4 md:grid-cols-4">

    <div class="flex items-center gap-4 p-4 bg-white border border-gray-100 shadow-sm rounded-xl">
        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl bg-teal-50">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase truncate">Încasate lunar</p>
            <p class="text-xl font-bold leading-tight text-gray-900">{{ number_format($revenue_ron, 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">RON</span></p>
            @if($revenue_eur > 0)<p class="text-xs text-gray-400">+ {{ number_format($revenue_eur, 0, ',', '.') }} EUR</p>@endif
        </div>
    </div>

    <div class="flex items-center gap-4 p-4 bg-white border border-gray-100 shadow-sm rounded-xl">
        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl bg-orange-50">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase truncate">Cheltuieli lunar</p>
            <p class="text-xl font-bold leading-tight text-gray-900">{{ number_format($expenses_ron, 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">RON</span></p>
            @if($expenses_eur > 0)<p class="text-xs text-gray-400">+ {{ number_format($expenses_eur, 0, ',', '.') }} EUR</p>@endif
        </div>
    </div>

    <div class="flex items-center gap-4 p-4 bg-white border border-gray-100 shadow-sm rounded-xl">
        <div class="w-10 h-10 rounded-xl {{ $profit_ron >= 0 ? 'bg-emerald-50' : 'bg-red-50' }} flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $profit_ron >= 0 ? 'text-emerald-600' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($profit_ron >= 0)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                @endif
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Profit net</p>
            <p class="text-xl font-bold leading-tight {{ $profit_ron >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                {{ number_format($profit_ron, 0, ',', '.') }} <span class="text-xs font-normal">RON</span>
            </p>
        </div>
    </div>

    <div class="flex items-center gap-4 p-4 bg-white border border-gray-100 shadow-sm rounded-xl">
        <div class="w-10 h-10 rounded-xl {{ $overdueCount > 0 ? 'bg-red-50' : 'bg-gray-50' }} flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-medium tracking-wide text-gray-400 uppercase">Facturi restante</p>
            <p class="text-xl font-bold leading-tight {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-900' }}">{{ $overdueCount }}</p>
            <p class="text-xs text-gray-400">{{ $invoiceStatusCounts['sent'] }} trimise · {{ $invoiceStatusCounts['draft'] }} draft</p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">

    <div class="p-4 bg-white border border-gray-100 shadow-sm md:col-span-2 rounded-xl">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Cash Flow, ultimele 6 luni</h3>
                <p class="text-xs text-gray-400">Venituri vs Cheltuieli (RON)</p>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500 inline-block"></span>Venituri</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-400 inline-block"></span>Cheltuieli</span>
            </div>
        </div>
        <div style="height:180px; position:relative;">
            <canvas id="cashFlowChart"></canvas>
        </div>
    </div>

    <div class="p-4 bg-white border border-gray-100 shadow-sm rounded-xl">
        <div class="mb-2">
            <h3 class="text-sm font-semibold text-gray-800">Statusuri facturi</h3>
            <p class="text-xs text-gray-400">Distribuție total</p>
        </div>
        <div style="height:140px; position:relative;">
            <canvas id="statusChart"></canvas>
        </div>
        @php
            $statusConfig = [
                'paid'      => ['label' => 'Încasate',  'color' => '#10b981'],
                'sent'      => ['label' => 'Trimise',   'color' => '#3b82f6'],
                'draft'     => ['label' => 'Draft',     'color' => '#94a3b8'],
                'overdue'   => ['label' => 'Restante',  'color' => '#ef4444'],
                'cancelled' => ['label' => 'Anulate',   'color' => '#f97316'],
            ];
            $totalInvoices = array_sum($invoiceStatusCounts);
        @endphp
        <div class="mt-3 space-y-1.5">
            @foreach($statusConfig as $key => $cfg)
                @if($invoiceStatusCounts[$key] > 0)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex-shrink-0 w-2 h-2 rounded-full" style="background:{{ $cfg['color'] }}"></span>
                        <span class="text-xs text-gray-500">{{ $cfg['label'] }}</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">{{ $invoiceStatusCounts[$key] }}</span>
                </div>
                @endif
            @endforeach
            @if($totalInvoices === 0)
                <p class="py-1 text-xs text-center text-gray-400">Nicio factură</p>
            @endif
        </div>
    </div>
</div>

<div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800">Ultimele facturi</h3>
        <a href="{{ route('invoices.index') }}" class="text-xs font-medium text-teal-600 hover:underline">Vezi toate →</a>
    </div>
    @if($recentInvoices->isEmpty())
        <div class="px-5 py-6 text-center">
            <p class="text-sm text-gray-400">Nicio factură creată încă.</p>
            <a href="{{ route('invoices.create') }}"
               class="inline-block mt-2 px-4 py-1.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                Creează prima factură
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
        <table class="w-full min-w-[520px] text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Număr</th>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Client</th>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Data</th>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-2.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wide">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentInvoices as $inv)
                <tr class="transition-colors hover:bg-gray-50">
                    <td class="px-5 py-2.5">
                        <a href="{{ route('invoices.show', $inv) }}" class="text-xs font-medium text-teal-700 hover:underline">{{ $inv->number }}</a>
                    </td>
                    <td class="px-5 py-2.5 text-xs text-gray-600">{{ $inv->client ? $inv->client->name : '-' }}</td>
                    <td class="px-5 py-2.5 text-xs text-gray-500">{{ $inv->issue_date->format('d.m.Y') }}</td>
                    <td class="px-5 py-2.5">
                        @php
                            // Determinam clasa CSS si eticheta badge-ului de status
                            if ($inv->status === 'paid') {
                                $badgeCls = 'bg-emerald-100 text-emerald-700';
                                $badgeLbl = 'Încasată';
                            } elseif ($inv->status === 'sent') {
                                $badgeCls = 'bg-blue-100 text-blue-700';
                                $badgeLbl = 'Trimisă';
                            } elseif ($inv->status === 'draft') {
                                $badgeCls = 'bg-gray-100 text-gray-600';
                                $badgeLbl = 'Draft';
                            } elseif ($inv->status === 'overdue') {
                                $badgeCls = 'bg-red-100 text-red-700';
                                $badgeLbl = 'Restantă';
                            } elseif ($inv->status === 'cancelled') {
                                $badgeCls = 'bg-orange-100 text-orange-700';
                                $badgeLbl = 'Anulată';
                            } else {
                                $badgeCls = 'bg-gray-100 text-gray-600';
                                $badgeLbl = $inv->status;
                            }
                        @endphp
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $badgeCls }}">{{ $badgeLbl }}</span>
                    </td>
                    <td class="px-5 py-2.5 text-right text-xs font-semibold text-gray-800">
                        {{ number_format($inv->total_with_vat > 0 ? $inv->total_with_vat : $inv->total, 2, ',', '.') }}
                        <span class="font-normal text-gray-400">{{ $inv->currency }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>{{-- sfarsit overflow-x-auto --}}
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlow = @json($cashFlow_ron);

    const gradRev = ctx.createLinearGradient(0, 0, 0, 180);
    gradRev.addColorStop(0, 'rgba(13,148,136,0.22)');
    gradRev.addColorStop(1, 'rgba(13,148,136,0)');

    const gradExp = ctx.createLinearGradient(0, 0, 0, 180);
    gradExp.addColorStop(0, 'rgba(249,115,22,0.15)');
    gradExp.addColorStop(1, 'rgba(249,115,22,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: cashFlow.map(m => m.month),
            datasets: [
                {
                    label: 'Venituri',
                    data: cashFlow.map(m => m.revenue),
                    borderColor: '#0d9488',
                    backgroundColor: gradRev,
                    borderWidth: 2,
                    pointBackgroundColor: '#0d9488',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Cheltuieli',
                    data: cashFlow.map(m => m.expenses),
                    borderColor: '#f97316',
                    backgroundColor: gradExp,
                    borderWidth: 2,
                    pointBackgroundColor: '#f97316',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: c => ' ' + c.dataset.label + ': ' + c.parsed.y.toLocaleString('ro-RO') + ' RON'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8', font: { size: 10 }, callback: v => v.toLocaleString('ro-RO') }
                }
            }
        }
    });
})();

(function () {
    const counts = @json($invoiceStatusCounts);
    const total  = Object.values(counts).reduce((a, b) => a + b, 0);
    if (total === 0) return;

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Încasate','Trimise','Draft','Restante','Anulate'],
            datasets: [{
                data: [counts.paid, counts.sent, counts.draft, counts.overdue, counts.cancelled],
                backgroundColor: ['#10b981','#3b82f6','#94a3b8','#ef4444','#f97316'],
                borderWidth: 0,
                hoverOffset: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: 8,
                    cornerRadius: 8,
                    callbacks: {
                        label: c => ' ' + c.label + ': ' + c.parsed + ' facturi'
                    }
                }
            }
        }
    });
})();
</script>

</x-cashly-layout>
