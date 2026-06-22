<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - Creează cont</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; }
        body { margin:0; font-family:ui-sans-serif,system-ui,sans-serif; overflow-x:hidden; }
        input:focus { outline:2px solid #0d9488; outline-offset:1px; border-color:#0d9488 !important; }
        .field { margin-bottom:16px; }
        .field label { display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px; }
        .field input { width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:14px;color:#0f172a;background:#fff; }
        .row { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
        .auth-left { display: flex; }
        @media (max-width:640px) {
            .auth-left { display:none !important; }
            .auth-right { padding:32px 20px !important; }
            .row { grid-template-columns:1fr !important; gap:0 !important; }
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
            <h2 style="font-size:28px;font-weight:800;color:#fff;margin:0 0 12px;line-height:1.2;">Începe gratuit,<br>fără card.</h2>
            <p style="font-size:15px;color:rgba(255,255,255,0.8);margin:0 0 36px;line-height:1.6;">Platforma completă pentru freelanceri și PFA-uri din România.</p>

            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach(['Cont gratuit, fără card de credit','Facturi PDF în 2 minute','Dashboard financiar complet','Categorii cheltuieli predefinite','Notificări automate pentru scadențe'] as $f)
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

    <div class="auth-right" style="flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#f8fafc;overflow-y:auto;">
        <div style="width:100%;max-width:440px;">

            <div style="margin-bottom:28px;">
                <h1 style="font-size:26px;font-weight:800;color:#0f172a;margin:0 0 6px;letter-spacing:-0.5px;">Creează cont gratuit</h1>
                <p style="font-size:14px;color:#64748b;margin:0;">Ai deja cont? <a href="{{ route('login') }}" style="color:#0d9488;font-weight:500;text-decoration:none;">Autentifică-te</a></p>
            </div>

            <a href="{{ route('auth.google') }}"
               style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:11px;background:#fff;color:#374151;font-weight:500;font-size:14px;border-radius:10px;text-decoration:none;border:1.5px solid #e2e8f0;margin-bottom:20px;">
                <svg width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Înregistrează-te cu Google
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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                    <div class="field">
                        <label>Nume <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Numele tău">
                    </div>
                    <div class="field">
                        <label>Firmă <span style="color:#94a3b8;font-weight:400;">(opțional)</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="SC Firma SRL">
                    </div>
                </div>

                <div class="field">
                    <label>CUI / VAT <span style="color:#94a3b8;font-weight:400;">(opțional)</span></label>
                    <input type="text" name="company_vat" value="{{ old('company_vat') }}" placeholder="RO12345678">
                </div>

                <div class="field">
                    <label>Email <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@exemplu.ro">
                </div>

                <div class="row">
                    <div class="field">
                        <label>Parolă <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password" required placeholder="Min. 8 caractere">
                    </div>
                    <div class="field">
                        <label>Confirmă parola <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Repetă parola">
                    </div>
                </div>

                <button type="submit"
                        style="width:100%;padding:12px;background:#0d9488;color:#fff;font-weight:600;font-size:15px;border:none;border-radius:10px;cursor:pointer;margin-top:4px;">
                    Creează cont gratuit
                </button>
            </form>
        </div>
    </div>

</body>
</html>
