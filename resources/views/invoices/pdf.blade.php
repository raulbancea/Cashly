<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            background: #fff;
        }
        .page {
            padding: 36px 44px 36px 44px;
            min-height: 1050px;
            display: block;
        }

        /* ── HEADER ── */
        .header-table { width: 100%; margin-bottom: 20px; }
        .header-table td { vertical-align: middle; }

        .logo-cell { width: 55%; vertical-align: middle; }
        .logo-cell img { max-height: 72px; max-width: 220px; }
        .logo-placeholder {
            font-size: 22px;
            font-weight: bold;
            color: #0d9488;
        }
        .company-sub { font-size: 11px; color: #555; margin-top: 4px; line-height: 1.6; }

        .title-cell { width: 45%; text-align: right; vertical-align: middle; }
        .invoice-title {
            font-size: 26px;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 1px;
        }
        .invoice-serie { font-size: 15px; font-weight: bold; color: #1a1a2e; margin-top: 4px; }
        .invoice-meta  { font-size: 11px; color: #555; margin-top: 5px; line-height: 1.8; }
        .invoice-meta span { font-weight: bold; color: #1a1a2e; }

        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 6px;
        }
        .badge-paid      { background:#d1fae5; color:#065f46; }
        .badge-draft     { background:#f3f4f6; color:#374151; }
        .badge-sent      { background:#dbeafe; color:#1e40af; }
        .badge-overdue   { background:#fee2e2; color:#991b1b; }
        .badge-cancelled { background:#ffedd5; color:#9a3412; }

        .divider {
            border: none;
            border-top: 3px solid #0d9488;
            margin-bottom: 22px;
        }

        /* ── PARTIES ── */
        .parties-table { width: 100%; margin-bottom: 22px; border-collapse: separate; border-spacing: 10px 0; }
        .party-cell { width: 50%; vertical-align: top; padding: 14px 16px; }
        .party-furnizor {
            background: #f0fdfa;
            border: 1.5px solid #99f6e4;
            border-radius: 6px;
        }
        .party-cumparator {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
        }
        .party-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0d9488;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #99f6e4;
        }
        .party-label-buyer { color: #64748b; border-bottom-color: #e2e8f0; }
        .party-name   { font-size: 14px; font-weight: bold; color: #1a1a2e; margin-bottom: 5px; }
        .party-detail { font-size: 11px; color: #555; margin-bottom: 3px; line-height: 1.5; }

        /* ── TVA LINE ── */
        .vat-line {
            font-size: 11px;
            color: #0d9488;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 6px 10px;
            background: #f0fdfa;
            border-left: 3px solid #0d9488;
            border-radius: 3px;
        }

        /* ── ITEMS TABLE ── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .items-table thead tr { background: #0d9488; }
        .items-table thead th {
            padding: 9px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
            text-align: left;
        }
        .items-table thead th.right  { text-align: right; }
        .items-table thead th.center { text-align: center; }

        .items-table tbody tr:nth-child(even) { background: #f8fafc; }
        .items-table tbody tr:nth-child(odd)  { background: #fff; }
        .items-table tbody td {
            padding: 10px 10px;
            font-size: 11px;
            color: #1a1a2e;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table tbody td.right  { text-align: right; }
        .items-table tbody td.center { text-align: center; }
        .items-table tbody td.nr     { color: #aaa; font-size: 10px; }

        /* ── TOTALS ── */
        .totals-wrap { border: 1px solid #e2e8f0; border-top: none; margin-bottom: 22px; }
        .totals-inner { width: 100%; border-collapse: collapse; }
        .totals-inner td { padding: 8px 10px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .t-label { text-align: right; color: #555; width: 78%; }
        .t-value { text-align: right; font-weight: bold; color: #1a1a2e; white-space: nowrap; width: 22%; }
        .totals-inner tr.grand td { background: #0d9488; border-bottom: none; padding: 11px 10px; }
        .totals-inner tr.grand td.t-label { font-size: 13px; font-weight: bold; color: #fff; }
        .totals-inner tr.grand td.t-value { font-size: 16px; font-weight: bold; color: #fff; }

        /* ── NOTES ── */
        .notes-box {
            margin-bottom: 22px;
            padding: 10px 14px;
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 3px;
        }
        .notes-label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #92400e; margin-bottom: 5px; }
        .notes-text  { font-size: 11px; color: #555; line-height: 1.6; }

        /* ── BOTTOM ── */
        .bottom-table { width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-top: 10px; }
        .bottom-cell  { vertical-align: top; width: 33.33%; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 6px; }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .bank-label { font-size: 10px; color: #888; margin-bottom: 3px; }
        .bank-value { font-size: 11px; font-weight: bold; color: #1a1a2e; margin-bottom: 10px; word-break: break-all; }

        /* ── STAMP SVG ── */
        .stamp-wrap { text-align: center; padding-top: 4px; }

        /* ── SIGNATURE ── */
        .sig-space { height: 55px; }
        .sig-line  { border-top: 1px solid #333; width: 85%; margin: 0 auto 5px auto; }
        .sig-label { font-size: 10px; color: #555; text-align: center; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px;
            padding-top: 10px;
            border-top: 2px solid #0d9488;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ════ HEADER ════ --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @php
                    $logoBase64 = null;
                    if ($invoice->user->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($invoice->user->logo)) {
                        $logoPath   = \Illuminate\Support\Facades\Storage::disk('public')->path($invoice->user->logo);
                        $finfo      = new \finfo(FILEINFO_MIME_TYPE);
                        $logoMime   = $finfo->file($logoPath);
                        $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo"><br>
                    <div class="company-sub">
                        @if($invoice->user->company_name)<strong>{{ $invoice->user->company_name }}</strong><br>@endif
                        @if($invoice->user->company_vat)CUI: {{ $invoice->user->company_vat }}<br>@endif
                        @if($invoice->user->address){{ $invoice->user->address }}@endif
                    </div>
                @else
                    <div class="logo-placeholder">{{ strtoupper($invoice->user->company_name ?? $invoice->user->name) }}</div>
                    <div class="company-sub">
                        @if($invoice->user->company_vat)CUI: {{ $invoice->user->company_vat }}<br>@endif
                        @if($invoice->user->address){{ $invoice->user->address }}<br>@endif
                        @if($invoice->user->phone)Tel: {{ $invoice->user->phone }}<br>@endif
                        @if($invoice->user->email){{ $invoice->user->email }}@endif
                    </div>
                @endif
            </td>
            <td class="title-cell">
                <div class="invoice-title">FACTURĂ FISCALĂ</div>
                <div class="invoice-serie">Nr. {{ $invoice->number }}</div>
                <div class="invoice-meta">
                    Data emiterii: <span>{{ $invoice->issue_date->format('d.m.Y') }}</span><br>
                    Scadentă: <span>{{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '—' }}</span><br>
                    Monedă: <span>{{ $invoice->currency }}</span>
                </div>
                <div>
                    <span class="badge badge-{{ $invoice->status }}">
                        {{ match($invoice->status) {
                            'paid'      => 'Încasată',
                            'draft'     => 'Draft',
                            'sent'      => 'Trimisă',
                            'overdue'   => 'Restantă',
                            'cancelled' => 'Anulată',
                            default     => $invoice->status
                        } }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- ════ FURNIZOR / CUMPĂRĂTOR ════ --}}
    <table class="parties-table">
        <tr>
            <td class="party-cell party-furnizor">
                <div class="party-label">Furnizor</div>
                <div class="party-name">{{ $invoice->user->company_name ?? $invoice->user->name }}</div>
                @if($invoice->user->company_vat)
                    <div class="party-detail">CUI / CIF: <strong>{{ $invoice->user->company_vat }}</strong></div>
                @endif
                @if($invoice->user->address)
                    <div class="party-detail">Adresă: {{ $invoice->user->address }}</div>
                @endif
                @if($invoice->user->phone)
                    <div class="party-detail">Telefon: {{ $invoice->user->phone }}</div>
                @endif
                @if($invoice->user->email)<div class="party-detail">Email: {{ $invoice->user->email }}</div>@endif
            </td>
            <td class="party-cell party-cumparator">
                <div class="party-label party-label-buyer">Cumpărător</div>
                <div class="party-name">{{ $invoice->client?->name ?? 'Client necunoscut' }}</div>
                @if($invoice->client?->cui)
                    <div class="party-detail">CUI / CIF: <strong>{{ $invoice->client->cui }}</strong></div>
                @endif
                @if($invoice->client?->address)
                    <div class="party-detail">Adresă: {{ $invoice->client->address }}</div>
                @endif
                @if($invoice->client?->phone)
                    <div class="party-detail">Telefon: {{ $invoice->client->phone }}</div>
                @endif
                @if($invoice->client?->email)
                    <div class="party-detail">Email: {{ $invoice->client->email }}</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- ════ TVA INFO ════ --}}
    @if($invoice->vat_rate)
        <div class="vat-line">Cotă TVA aplicată: {{ (int)$invoice->vat_rate }}%</div>
    @endif

    {{-- ════ TABEL PRODUSE ════ --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:4%;">#</th>
                <th style="width:{{ $invoice->vat_rate ? '36%' : '46%' }};">Denumire produs / serviciu</th>
                <th class="center" style="width:7%;">U.M.</th>
                <th class="right" style="width:9%;">Cant.</th>
                <th class="right" style="width:13%;">Preț unitar</th>
                @if($invoice->vat_rate)
                    <th class="right" style="width:13%;">Val. fără TVA</th>
                    <th class="right" style="width:13%;">Valoare TVA</th>
                @else
                    <th class="right" style="width:21%;">Total</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $i => $item)
                <tr>
                    <td class="nr">{{ $i + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="center">buc</td>
                    <td class="right">{{ number_format($item->quantity, 2, '.', '') }}</td>
                    <td class="right">{{ number_format($item->unit_price, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    @if($invoice->vat_rate)
                        <td class="right">{{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                        <td class="right">{{ number_format($item->total * $invoice->vat_rate / 100, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    @else
                        <td class="right">{{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    @endif
                </tr>
            @endforeach

            {{-- Rânduri goale pentru aspect profesional (minim 5 rânduri) --}}
            @for($e = count($invoice->items); $e < 5; $e++)
                <tr>
                    <td class="nr">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if($invoice->vat_rate)
                        <td></td><td></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endfor
        </tbody>
    </table>

    {{-- ════ TOTALURI ════ --}}
    <div class="totals-wrap">
        <table class="totals-inner">
            <tr>
                <td class="t-label">Subtotal (fără TVA):</td>
                <td class="t-value">{{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
            </tr>
            @if($invoice->vat_rate)
                <tr>
                    <td class="t-label">TVA {{ (int)$invoice->vat_rate }}%:</td>
                    <td class="t-value">{{ number_format($invoice->vat_amount, 2, ',', '.') }} {{ $invoice->currency }}</td>
                </tr>
                <tr class="grand">
                    <td class="t-label">TOTAL DE PLATĂ:</td>
                    <td class="t-value">{{ number_format($invoice->total_with_vat, 2, ',', '.') }} {{ $invoice->currency }}</td>
                </tr>
            @else
                <tr class="grand">
                    <td class="t-label">TOTAL DE PLATĂ:</td>
                    <td class="t-value">{{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                </tr>
            @endif
        </table>
    </div>

    {{-- ════ NOTE ════ --}}
    @if($invoice->notes)
        <div class="notes-box">
            <div class="notes-label">Mențiuni / Note</div>
            <div class="notes-text">{{ $invoice->notes }}</div>
        </div>
    @endif

    {{-- ════ BOTTOM: CONT BANCAR | ȘTAMPILĂ | SEMNĂTURĂ ════ --}}
    <table class="bottom-table">
        <tr>
            {{-- Date plată --}}
            <td class="bottom-cell">
                <div class="section-title">Date plată</div>
                @if($invoice->user->bank_account)
                    <div class="bank-label">Cont IBAN:</div>
                    <div class="bank-value">{{ $invoice->user->bank_account }}</div>
                @endif
                <div class="bank-label">Beneficiar:</div>
                <div class="bank-value">{{ $invoice->user->company_name ?? $invoice->user->name }}</div>
                @if($invoice->user->company_vat)
                    <div class="bank-label">CUI:</div>
                    <div class="bank-value">{{ $invoice->user->company_vat }}</div>
                @endif
            </td>

            {{-- Ștampilă digitală --}}
            <td class="bottom-cell" style="text-align:center;">
                <div class="section-title" style="text-align:center;">Ștampilă</div>
                @php
                    $stampName = mb_strtoupper(mb_substr($invoice->user->company_name ?? $invoice->user->name, 0, 20));
                    $stampCui  = $invoice->user->company_vat ? 'CUI: ' . $invoice->user->company_vat : '';
                    $stampDate = $invoice->issue_date->format('d.m.Y');
                @endphp
                <div class="stamp-wrap">
                    <svg width="140" height="140" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
                        <circle cx="70" cy="70" r="66" fill="none" stroke="#0d9488" stroke-width="3"/>
                        <circle cx="70" cy="70" r="57" fill="none" stroke="#0d9488" stroke-width="1"/>
                        <text x="70" y="28"  font-family="DejaVu Sans" font-size="9"  fill="#0d9488" text-anchor="middle" font-weight="bold">✦ CASHLY ✦</text>
                        <text x="70" y="60"  font-family="DejaVu Sans" font-size="9"  fill="#0d9488" text-anchor="middle" font-weight="bold">{{ $stampName }}</text>
                        @if($stampCui)
                        <text x="70" y="74"  font-family="DejaVu Sans" font-size="8.5" fill="#0d9488" text-anchor="middle">{{ $stampCui }}</text>
                        @endif
                        <text x="70" y="90"  font-family="DejaVu Sans" font-size="10" fill="#0d9488" text-anchor="middle" font-weight="bold">{{ $stampDate }}</text>
                        <text x="70" y="110" font-family="DejaVu Sans" font-size="7.5" fill="#0d9488" text-anchor="middle">DOCUMENT EMIS ELECTRONIC</text>
                    </svg>
                </div>
            </td>

            {{-- Semnătură --}}
            <td class="bottom-cell" style="text-align:center;">
                <div class="section-title" style="text-align:center;">Semnătură furnizor</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div class="sig-label">{{ $invoice->user->company_name ?? $invoice->user->name }}</div>
            </td>
        </tr>
    </table>

    {{-- ════ FOOTER ════ --}}
    <div class="footer">
        Document generat electronic cu <strong>Cashly</strong> &bull; {{ date('d.m.Y H:i') }}<br>
        Acest document este valabil fără semnătură olografă conform Legii nr. 227/2015 privind Codul Fiscal.
    </div>

</div>
</body>
</html>
