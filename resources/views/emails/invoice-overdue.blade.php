<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Factură restantă {{ $invoice->number }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 0; color: #374151; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #dc2626; padding: 32px 40px; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; }
        .header p { margin: 4px 0 0; color: #fca5a5; font-size: 14px; }
        .body { padding: 32px 40px; }
        .greeting { font-size: 16px; margin-bottom: 24px; color: #111827; }
        .alert-box { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .alert-box table { width: 100%; border-collapse: collapse; }
        .alert-box td { padding: 6px 0; font-size: 14px; }
        .alert-box td:first-child { color: #6b7280; width: 140px; }
        .alert-box td:last-child { font-weight: 600; color: #111827; }
        .total-row td { font-size: 16px !important; color: #dc2626 !important; padding-top: 12px !important; border-top: 1px solid #fca5a5; }
        .note { font-size: 13px; color: #6b7280; margin-bottom: 24px; line-height: 1.6; }
        .footer { padding: 20px 40px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Cashly: Factură restantă</h1>
            <p>Termenul de plată a fost depășit</p>
        </div>
        <div class="body">
            <p class="greeting">Bună ziua, <strong>{{ $invoice->client->name }}</strong>,</p>
            <p class="note">
                Vă aducem la cunoștință că factura de mai jos nu a fost achitată până la termenul scadent.
                Vă rugăm să efectuați plata cât mai curând posibil.
            </p>
            <div class="alert-box">
                <table>
                    <tr><td>Număr factură:</td><td>{{ $invoice->number }}</td></tr>
                    <tr><td>Data scadenței:</td><td>{{ $invoice->due_date->format('d.m.Y') }}</td></tr>
                    <tr><td>Zile restante:</td><td>{{ $invoice->due_date->diffInDays(now()) }} zile</td></tr>
                    <tr class="total-row">
                        <td>Total restant:</td>
                        <td>{{ number_format($invoice->vat_rate ? $invoice->total_with_vat : $invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    </tr>
                </table>
            </div>
            <p class="note">Pentru orice nelămuriri, nu ezitați să ne contactați.</p>
        </div>
        <div class="footer">
            Acest email a fost generat automat de <strong>Cashly</strong>.<br>
            &copy; {{ date('Y') }} Cashly. Toate drepturile rezervate.
        </div>
    </div>
</body>
</html>
