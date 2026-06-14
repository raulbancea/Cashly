<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - Resetare parolă</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; }
        body { margin:0; font-family:ui-sans-serif,system-ui,sans-serif; overflow-x:hidden; }
        input:focus { outline:2px solid #0d9488; outline-offset:1px; border-color:#0d9488 !important; }
        /* DESKTOP: panoul stang este flex container */
        .auth-left { display: flex; }
        /* MOBIL: ascundem panoul stang */
        @media (max-width:640px) {
            .auth-left { display:none !important; }
            .auth-right { padding:32px 20px !important; }
        }
    </style>
</head>
<body style="min-height:100vh;display:flex;">

    <div class="auth-left" style="width:420px;flex-shrink:0;background:linear-gradient(160deg,#0d9488 0%,#0891b2 100%);padding:48px 40px;flex-direction:column;justify-content:space-between;">
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <img src="{{ asset('logo.png') }}" alt="Cashly" style="height:34px;width:auto;">
            <span style="font-size:20px;font-weight:800;color:#fff;letter-spacing:-0.5px;">Cashly</span>
        </a>

        <div>
            <h2 style="font-size:26px;font-weight:800;color:#fff;margin:0 0 12px;line-height:1.2;">Resetare parolă</h2>
            <p style="font-size:15px;color:rgba(255,255,255,0.8);margin:0;line-height:1.6;">Îți trimitem un link securizat pe email pentru a-ți reseta parola contului Cashly.</p>
        </div>

        <p style="font-size:12px;color:rgba(255,255,255,0.5);margin:0;">© {{ date('Y') }} Cashly. Toate drepturile rezervate.</p>
    </div>

    <div class="auth-right" style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#f8fafc;">
        <div style="width:100%;max-width:400px;">

            <div style="margin-bottom:32px;">
                <h1 style="font-size:26px;font-weight:800;color:#0f172a;margin:0 0 6px;letter-spacing:-0.5px;">Ai uitat parola?</h1>
                <p style="font-size:14px;color:#64748b;margin:0;line-height:1.6;">Introdu adresa de email și îți trimitem un link pentru a-ți seta o parolă nouă.</p>
            </div>

            @if(session('status'))
                <div style="background:#f0fdfa;border:1px solid #5eead4;border-radius:9px;padding:14px;margin-bottom:24px;display:flex;align-items:flex-start;gap:10px;">
                    <svg width="18" height="18" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="color:#0d9488;font-size:14px;margin:0;">{{ session('status') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:12px;margin-bottom:20px;">
                    @foreach($errors->all() as $error)
                        <p style="color:#ef4444;font-size:13px;margin:2px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Adresă email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="email@exemplu.ro"
                           style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:14px;color:#0f172a;background:#fff;box-sizing:border-box;">
                </div>

                <button type="submit"
                        style="width:100%;padding:12px;background:#0d9488;color:#fff;font-weight:600;font-size:15px;border:none;border-radius:10px;cursor:pointer;margin-bottom:16px;">
                    Trimite link de resetare
                </button>

                <a href="{{ route('login') }}"
                   style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:14px;color:#64748b;text-decoration:none;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Înapoi la autentificare
                </a>
            </form>
        </div>
    </div>

</body>
</html>
