<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Resetare parolă</title>
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
            Înapoi la autentificare
        </a>
    </nav>

    {{-- Form --}}
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
        <div style="width: 100%; max-width: 440px;">

            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 28px; font-weight: 700; color: white; margin-bottom: 8px;">Ai uitat parola?</h1>
                <p style="color: #6b7280; font-size: 14px;">Introdu emailul și îți trimitem un link de resetare.</p>
            </div>

            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px;">

                @if(session('status'))
                    <div style="background: rgba(20,184,166,0.1); border: 1px solid rgba(20,184,166,0.3); border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                        <p style="color: #5eead4; font-size: 13px;">{{ session('status') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                        @foreach($errors->all() as $error)
                            <p style="color: #fca5a5; font-size: 13px; margin: 2px 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #d1d5db; margin-bottom: 6px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               style="width: 100%; padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                               placeholder="email@exemplu.ro">
                    </div>

                    <button type="submit"
                            style="width: 100%; padding: 12px; background: #14b8a6; color: white; font-weight: 600; font-size: 15px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 0 20px rgba(20, 184, 166, 0.4);">
                        Trimite link de resetare
                    </button>
                </form>
            </div>

            <p style="text-align: center; margin-top: 24px; font-size: 14px; color: #6b7280;">
                Îți amintești parola?
                <a href="{{ route('login') }}" style="color: #14b8a6; text-decoration: none;">Autentifică-te</a>
            </p>
        </div>
    </div>

</body>
</html>
