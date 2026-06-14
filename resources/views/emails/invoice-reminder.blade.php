<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Reminder scadență {{ $invoice->number }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 0; color: #374151; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #d97706; padding: 32px 40px; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; }
        .header p { margin: 4px 0 0; color: #fde68a; font-size: 14px; }
        .body { padding: 32px 40px; }
        .greeting { font-size: 16px; margin-bottom: 24px; color: #111827; }
        .reminder-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .reminder-box table { width: 100%; border-collapse: collapse; }
        .reminder-box td { padding: 6px 0; font-size: 14px; }
        .reminder-box td:first-child { color: #6b7280; width: 140px; }
        .reminder-box td:last-child { font-weight: 600; color: #111827; }
        .total-row td { font-size: 16px !important; color: #d97706 !important; padding-top: 12px !important; border-top: 1px solid #fde68a; }
        .note { font-size: 13px; color: #6b7280; margin-bottom: 24px; line-height: 1.6; }
        .footer { padding: 20px 40px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Cashly: Reminder plată</h1>
            <p>Factura este scadentă în {{ $daysLeft }} {{ $daysLeft === 1 ? 'zi' : 'zile' }}</p>
        </div>
        <div class="body">
            <p class="greeting">Bună ziua, <strong>{{ $invoice->client ? $invoice->client->name : 'Client' }}</strong>,</p>
            <p class="note">
                Vă reamintim că factura de mai jos este scadentă în <strong>{{ $daysLeft }} {{ $daysLeft === 1 ? 'zi' : 'zile' }}</strong>.
                Vă rugăm să efectuați plata înainte de termenul limită.
            </p>
            <div class="reminder-box">
                <table>
                    <tr><td>Număr factură:</td><td>{{ $invoice->number }}</td></tr>
                    <tr><td>Scadență:</td><td>{{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}</td></tr>
                    <tr><td>Zile rămase:</td><td>{{ $daysLeft }} {{ $daysLeft === 1 ? 'zi' : 'zile' }}</td></tr>
                    <tr class="total-row">
                        <td>Total de plată:</td>
                        <td>{{ number_format($invoice->vat_rate ? $invoice->total_with_vat : $invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    </tr>
                </table>
            </div>
            <p class="note">Vă mulțumim pentru colaborare!</p>
        </div>
        <div class="footer">
            Acest email a fost generat automat de <strong>Cashly</strong>.<br>
            &copy; {{ date('Y') }} Cashly. Toate drepturile rezervate.
        </div>
    </div>
</body>
</html>
