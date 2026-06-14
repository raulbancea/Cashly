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
        .field { margin-bottom:18px; }
        .field label { display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px; }
        .field input { width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:14px;color:#0f172a;background:#fff; }
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
            <h2 style="font-size:28px;font-weight:800;color:#fff;margin:0 0 12px;line-height:1.2;">Resetare parolă</h2>
            <p style="font-size:15px;color:rgba(255,255,255,0.8);margin:0 0 36px;line-height:1.6;">Alege o parolă nouă și sigură pentru contul tău Cashly.</p>

            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach(['Parolă securizată și criptată','Acces imediat după resetare','Contul tău rămâne protejat','Suport disponibil oricând'] as $f)
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:22px;height:22px;min-width:22px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span style="font-size:14px;color:rgba(255,255,255,0.9);">{{ $f }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <p style="font-size:12px;color:rgba(255,255,255,0.5);margin:0;">© {{ date('Y') }} Cashly. Toate drepturile rezervate.</p>
    </div>

    <div class="auth-right" style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#f8fafc;">
        <div style="width:100%;max-width:420px;">

            <div style="margin-bottom:32px;">
                <h1 style="font-size:26px;font-weight:800;color:#0f172a;margin:0 0 6px;letter-spacing:-0.5px;">Parolă nouă</h1>
                <p style="font-size:14px;color:#64748b;margin:0;">Introdu noua ta parolă mai jos.</p>
            </div>

            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:12px;margin-bottom:20px;">
                    @foreach($errors->all() as $error)
                        <p style="color:#ef4444;font-size:13px;margin:2px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus placeholder="email@exemplu.ro">
                </div>

                <div class="field">
                    <label>Parolă nouă</label>
                    <input type="password" name="password" required placeholder="Minim 8 caractere">
                </div>

                <div class="field">
                    <label>Confirmă parola</label>
                    <input type="password" name="password_confirmation" required placeholder="Repetă parola nouă">
                </div>

                <button type="submit"
                        style="width:100%;padding:12px;background:#0d9488;color:#fff;font-weight:600;font-size:15px;border:none;border-radius:10px;cursor:pointer;margin-top:6px;">
                    Resetează parola
                </button>

                <p style="text-align:center;margin-top:20px;font-size:13px;color:#64748b;">
                    <a href="{{ route('login') }}" style="color:#0d9488;font-weight:500;text-decoration:none;">Înapoi la autentificare</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>
