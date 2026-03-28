<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0d9488;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
        }
        .invoice-number {
            color: #6b7280;
            text-align: right;
        }
        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .party-box {
            width: 45%;
        }
        .party-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .party-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .party-detail {
            color: #6b7280;
            margin-bottom: 2px;
        }
        .dates-box {
            background-color: #f9fafb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
        }
        .date-item label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            display: block;
            margin-bottom: 3px;
        }
        .date-item span {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #f3f4f6;
        }
        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }
        thead th.right {
            text-align: right;
        }
        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #f3f4f6;
        }
        tbody td.right {
            text-align: right;
        }
        .total-row {
            border-top: 2px solid #e5e7eb;
        }
        .total-row td {
            padding: 12px;
            font-weight: bold;
            font-size: 14px;
        }
        .total-amount {
            text-align: right;
            color: #0d9488;
            font-size: 18px;
        }
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 6px;
        }
        .notes-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-paid { background-color: #d1fae5; color: #065f46; }
        .status-draft { background-color: #f3f4f6; color: #374151; }
        .status-sent { background-color: #dbeafe; color: #1e40af; }
        .status-overdue { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">{{ $invoice->user->company_name ?? $invoice->user->name }}</div>
            @if($invoice->user->company_vat)
                <div style="color: #6b7280;">CUI: {{ $invoice->user->company_vat }}</div>
            @endif
            @if($invoice->user->address)
                <div style="color: #6b7280;">{{ $invoice->user->address }}</div>
            @endif
        </div>
        <div>
            <div class="invoice-title">FACTURĂ</div>
            <div class="invoice-number">{{ $invoice->number }}</div>
            <div style="margin-top: 8px;">
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ match($invoice->status) {
                        'paid' => 'Încasată',
                        'draft' => 'Draft',
                        'sent' => 'Trimisă',
                        'overdue' => 'Restantă',
                        default => $invoice->status
                    } }}
                </span>
            </div>
        </div>
    </div>

    {{-- Parti --}}
    <div class="parties">
        <div class="party-box">
            <div class="party-label">Furnizor</div>
            <div class="party-name">{{ $invoice->user->company_name ?? $invoice->user->name }}</div>
            @if($invoice->user->email)
                <div class="party-detail">{{ $invoice->user->email }}</div>
            @endif
        </div>
        <div class="party-box">
            <div class="party-label">Client</div>
            <div class="party-name">{{ $invoice->client->name }}</div>
            @if($invoice->client->cui)
                <div class="party-detail">CUI: {{ $invoice->client->cui }}</div>
            @endif
            @if($invoice->client->email)
                <div class="party-detail">{{ $invoice->client->email }}</div>
            @endif
            @if($invoice->client->address)
                <div class="party-detail">{{ $invoice->client->address }}</div>
            @endif
        </div>
    </div>

    {{-- Date --}}
    <div class="dates-box">
        <div class="date-item">
            <label>Data emiterii</label>
            <span>{{ $invoice->issue_date->format('d.m.Y') }}</span>
        </div>
        <div class="date-item">
            <label>Scadență</label>
            <span>{{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}</span>
        </div>
        <div class="date-item">
            <label>Monedă</label>
            <span>{{ $invoice->currency }}</span>
        </div>
    </div>

    {{-- Linii --}}
    <table>
        <thead>
            <tr>
                <th>Descriere</th>
                <th class="right">Cantitate</th>
                <th class="right">Preț unitar</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->unit_price, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    <td class="right">{{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total factură:</td>
                <td class="total-amount">{{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
            </tr>
        </tbody>
    </table>

    @if($invoice->notes)
        <div class="notes">
            <div class="notes-label">Note</div>
            <div>{{ $invoice->notes }}</div>
        </div>
    @endif

    <div class="footer">
        Generat cu Cashly &bull; {{ date('d.m.Y') }}
    </div>

</div>
</body>
</html>
