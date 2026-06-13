{{-- Template email pentru trimiterea unei facturi clientului --}}
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factură {{ $invoice->number }}</title>
    {{-- Stiluri inline pentru compatibilitate maxima cu clientii de email --}}
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 0; color: #374151; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #0d9488; padding: 32px 40px; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; }
        .header p { margin: 4px 0 0; color: #99f6e4; font-size: 14px; }
        .body { padding: 32px 40px; }
        .greeting { font-size: 16px; margin-bottom: 24px; color: #111827; }
        .invoice-box { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .invoice-box table { width: 100%; border-collapse: collapse; }
        .invoice-box td { padding: 6px 0; font-size: 14px; }
        .invoice-box td:first-child { color: #6b7280; width: 140px; }
        .invoice-box td:last-child { font-weight: 600; color: #111827; }
        .total-row td { font-size: 16px !important; color: #0d9488 !important; padding-top: 12px !important; border-top: 1px solid #99f6e4; margin-top: 8px; }
        .note { font-size: 13px; color: #6b7280; margin-bottom: 24px; line-height: 1.6; }
        .footer { padding: 20px 40px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Cashly</h1>
            <p>Factură nouă pentru tine</p>
        </div>
        <div class="body">
            <p class="greeting">Bună ziua, <strong>{{ $invoice->client->name }}</strong>,</p>

            <p class="note">
                Vă transmitem alăturat factura <strong>{{ $invoice->number }}</strong>.
                Vă rugăm să găsiți documentul PDF atașat acestui email.
            </p>

            <div class="invoice-box">
                <table>
                    <tr>
                        <td>Număr factură:</td>
                        <td>{{ $invoice->number }}</td>
                    </tr>
                    <tr>
                        <td>Data emiterii:</td>
                        <td>{{ $invoice->issue_date->format('d.m.Y') }}</td>
                    </tr>
                    @if($invoice->due_date)
                    <tr>
                        <td>Scadență:</td>
                        <td>{{ $invoice->due_date->format('d.m.Y') }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total de plată:</td>
                        <td>
                            {{ number_format($invoice->vat_rate ? $invoice->total_with_vat : $invoice->total, 2, ',', '.') }}
                            {{ $invoice->currency }}
                        </td>
                    </tr>
                </table>
            </div>

            <p class="note">
                Pentru orice întrebări referitoare la această factură, nu ezitați să ne contactați.<br>
                Vă mulțumim pentru colaborare!
            </p>
        </div>
        <div class="footer">
            Acest email a fost generat automat de <strong>Cashly</strong>.<br>
            &copy; {{ date('Y') }} Cashly. Toate drepturile rezervate.
        </div>
    </div>
</body>
</html>
