<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Autentificare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #030712; min-height: 100vh; display: flex; flex-direction: column; font-family: sans-serif;">

    {{-- Navbar --}}
    <nav style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0 24px; height: 64px; display: flex; align-items: center; justify-content: space-between;">
        <a href="/" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <div style="width: 28px; height: 28px; background: #14b8a6; border-radius: 8px;"></div>
            <span style="color: white; font-weight: 700; font-size: 18px;">Cashly</span>
        </a>
        <a href="{{ route('register') }}" style="font-size: 14px; color: #9ca3af; text-decoration: none;">
            Nu ai cont? Înregistrează-te
        </a>
    </nav>

    {{-- Form --}}
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
        <div style="width: 100%; max-width: 440px;">

            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 28px; font-weight: 700; color: white; margin-bottom: 8px;">Bun venit înapoi</h1>
                <p style="color: #6b7280; font-size: 14px;">Intră în contul tău Cashly</p>
            </div>

            {{-- Buton Google --}}
            <a href="{{ route('auth.google') }}"
               style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 11px; background: white; color: #111827; font-weight: 600; font-size: 14px; border: none; border-radius: 10px; cursor: pointer; text-decoration: none; margin-bottom: 20px;">
                <svg width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    <path fill="none" d="M0 0h48v48H0z"/>
                </svg>
                Continuă cu Google
            </a>

            {{-- Separator --}}
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.08);"></div>
                <span style="font-size: 12px; color: #4b5563;">sau cu email</span>
                <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.08);"></div>
            </div>

            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px;">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if($errors->any())
                        <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                            @foreach($errors->all() as $error)
                                <p style="color: #fca5a5; font-size: 13px; margin: 2px 0;">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="email@exemplu.ro">
                    </div>

                    <div style="margin-bottom: 8px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Parolă</label>
                        <input type="password" name="password" required
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="Parola ta">
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="remember"
                                   style="width: 14px; height: 14px; accent-color: #14b8a6;">
                            <span style="font-size: 13px; color: #9ca3af;">Ține-mă minte</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               style="font-size: 13px; color: #14b8a6; text-decoration: none;">
                                Ai uitat parola?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            style="width: 100%; padding: 12px; background: #14b8a6; color: white; font-weight: 600; font-size: 15px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 0 20px rgba(20, 184, 166, 0.4);">
                        Autentifică-te
                    </button>
                </form>
            </div>

            <p style="text-align: center; margin-top: 24px; font-size: 14px; color: #6b7280;">
                Nu ai cont?
                <a href="{{ route('register') }}" style="color: #14b8a6; text-decoration: none;">Creează unul gratuit</a>
            </p>
        </div>
    </div>

</body>
</html>
