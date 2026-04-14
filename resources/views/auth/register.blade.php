<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Creează cont</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #030712; min-height: 100vh; display: flex; flex-direction: column; font-family: sans-serif;">

    {{-- Navbar --}}
    <nav style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0 24px; height: 64px; display: flex; align-items: center; justify-content: space-between;">
        <a href="/" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <div style="width: 28px; height: 28px; background: #14b8a6; border-radius: 8px;"></div>
            <span style="color: white; font-weight: 700; font-size: 18px;">Cashly</span>
        </a>
        <a href="{{ route('login') }}" style="font-size: 14px; color: #9ca3af; text-decoration: none;">
            Ai deja cont? Autentifică-te
        </a>
    </nav>

    {{-- Form --}}
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
        <div style="width: 100%; max-width: 440px;">

            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 28px; font-weight: 700; color: white; margin-bottom: 8px;">Creează cont gratuit</h1>
                <p style="color: #6b7280; font-size: 14px;">Fără card de credit. Fără angajamente.</p>
            </div>

            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px;">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    @if($errors->any())
                        <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                            @foreach($errors->all() as $error)
                                <p style="color: #fca5a5; font-size: 13px; margin: 2px 0;">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Nume *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="Numele tău">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Nume firmă</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="SC Firma SRL (opțional)">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">CUI / VAT</label>
                        <input type="text" name="company_vat" value="{{ old('company_vat') }}"
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="RO12345678 (opțional)">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="email@exemplu.ro">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Parolă *</label>
                        <input type="password" name="password" required
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="Minim 8 caractere">
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Confirmă parola *</label>
                        <input type="password" name="password_confirmation" required
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="Repetă parola">
                    </div>

                    <button type="submit"
                            style="width: 100%; padding: 12px; background: #14b8a6; color: white; font-weight: 600; font-size: 15px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 0 20px rgba(20, 184, 166, 0.4);">
                        Creează cont gratuit
                    </button>
                </form>
            </div>

            <p style="text-align: center; margin-top: 24px; font-size: 14px; color: #6b7280;">
                Ai deja cont?
                <a href="{{ route('login') }}" style="color: #14b8a6; text-decoration: none;">Autentifică-te</a>
            </p>
        </div>
    </div>

</body>
</html>
