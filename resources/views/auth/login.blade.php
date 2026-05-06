<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - Autentificare</title>
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; }
        body { margin:0; font-family:ui-sans-serif,system-ui,sans-serif; }
        input:focus { outline:2px solid #0d9488; outline-offset:1px; border-color:#0d9488 !important; }
        .field { margin-bottom:18px; }
        .field label { display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px; }
        .field input { width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:14px;color:#0f172a;background:#fff; }
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
            <h2 style="font-size:28px;font-weight:800;color:#fff;margin:0 0 12px;line-height:1.2;">Bun venit înapoi!</h2>
            <p style="font-size:15px;color:rgba(255,255,255,0.8);margin:0 0 36px;line-height:1.6;">Intră în cont și continuă de unde ai rămas.</p>

            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach(['Facturi PDF profesionale','Dashboard financiar în timp real','Gestiune clienți și cheltuieli','Notificări automate scadențe'] as $f)
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

    {{-- RIGHT PANEL --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#f8fafc;">
        <div style="width:100%;max-width:420px;">

            <div style="margin-bottom:32px;">
                <h1 style="font-size:26px;font-weight:800;color:#0f172a;margin:0 0 6px;letter-spacing:-0.5px;">Autentifică-te</h1>
                <p style="font-size:14px;color:#64748b;margin:0;">Nu ai cont? <a href="{{ route('register') }}" style="color:#0d9488;font-weight:500;text-decoration:none;">Creează unul gratuit</a></p>
            </div>

            {{-- Google --}}
            <a href="{{ route('auth.google') }}"
               style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:11px;background:#fff;color:#374151;font-weight:500;font-size:14px;border-radius:10px;text-decoration:none;border:1.5px solid #e2e8f0;margin-bottom:20px;">
                <svg width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Continuă cu Google
            </a>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <div style="flex:1;height:1px;background:#e2e8f0;"></div>
                <span style="font-size:12px;color:#94a3b8;">sau cu email</span>
                <div style="flex:1;height:1px;background:#e2e8f0;"></div>
            </div>

            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:12px;margin-bottom:20px;">
                    @foreach($errors->all() as $error)
                        <p style="color:#ef4444;font-size:13px;margin:2px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="email@exemplu.ro">
                </div>

                <div class="field">
                    <label>Parolă</label>
                    <input type="password" name="password" required placeholder="Parola ta">
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;font-size:13px;color:#64748b;">
                        <input type="checkbox" name="remember" style="accent-color:#0d9488;width:14px;height:14px;">
                        Ține-mă minte
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:13px;color:#0d9488;text-decoration:none;font-weight:500;">Ai uitat parola?</a>
                    @endif
                </div>

                <button type="submit"
                        style="width:100%;padding:12px;background:#0d9488;color:#fff;font-weight:600;font-size:15px;border:none;border-radius:10px;cursor:pointer;">
                    Autentifică-te
                </button>
            </form>
        </div>
    </div>

</body>
</html>
