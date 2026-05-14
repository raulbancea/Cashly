<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - Verificare email</title>
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; }
        body { margin:0; font-family:ui-sans-serif,system-ui,sans-serif; }
    </style>
</head>
<body style="min-height:100vh;display:flex;">

    {{-- LEFT PANEL --}}
    <div style="width:420px;flex-shrink:0;background:linear-gradient(160deg,#0d9488 0%,#0891b2 100%);padding:48px 40px;display:flex;flex-direction:column;justify-content:space-between;">
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:34px;height:34px;background:rgba(255,255,255,0.2);border-radius:9px;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span style="font-size:20px;font-weight:800;color:#fff;letter-spacing:-0.5px;">Cashly</span>
        </a>

        <div>
            <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <svg width="28" height="28" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 style="font-size:26px;font-weight:800;color:#fff;margin:0 0 12px;line-height:1.2;">Un pas mic mai ramas</h2>
            <p style="font-size:15px;color:rgba(255,255,255,0.8);margin:0 0 32px;line-height:1.6;">Confirma adresa de email pentru a activa contul tau Cashly si a incepe sa folosesti platforma.</p>

            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach(['Securitatea contului tau este prioritatea noastra','Emailul de confirmare expira in 60 de minute','Poti solicita un nou email oricand'] as $f)
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:20px;height:20px;min-width:20px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <svg width="11" height="11" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span style="font-size:13px;color:rgba(255,255,255,0.85);">{{ $f }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <p style="font-size:12px;color:rgba(255,255,255,0.5);margin:0;">© {{ date('Y') }} Cashly. Toate drepturile rezervate.</p>
    </div>

    {{-- RIGHT PANEL --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#f8fafc;">
        <div style="width:100%;max-width:420px;">

            <div style="text-align:center;margin-bottom:32px;">
                <div style="width:64px;height:64px;background:#f0fdfa;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg width="30" height="30" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 style="font-size:24px;font-weight:800;color:#0f172a;margin:0 0 8px;letter-spacing:-0.5px;">Verifica-ti emailul</h1>
                <p style="font-size:14px;color:#64748b;margin:0;line-height:1.6;">
                    Ti-am trimis un link de confirmare la adresa ta de email.<br>
                    Acceseaza-l pentru a activa contul.
                </p>
            </div>

            @if(session('status') == 'verification-link-sent')
                <div style="background:#f0fdfa;border:1px solid #5eead4;border-radius:9px;padding:14px;margin-bottom:24px;display:flex;align-items:flex-start;gap:10px;">
                    <svg width="18" height="18" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="color:#0d9488;font-size:14px;margin:0;">Un nou link de verificare a fost trimis pe adresa ta de email.</p>
                </div>
            @endif

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:20px;">
                <p style="font-size:13px;color:#64748b;margin:0 0 4px;">Nu ai primit emailul? Verifica si folderul Spam.<br>Sau trimite un nou link:</p>
            </div>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        style="width:100%;padding:12px;background:#0d9488;color:#fff;font-weight:600;font-size:15px;border:none;border-radius:10px;cursor:pointer;margin-bottom:16px;">
                    Retrimite emailul de verificare
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        style="width:100%;padding:11px;background:transparent;color:#64748b;font-size:14px;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer;">
                    Deconectare
                </button>
            </form>
        </div>
    </div>

</body>
</html>
