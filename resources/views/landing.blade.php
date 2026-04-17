<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Gestiune financiară pentru freelanceri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .float { animation: float 6s ease-in-out infinite; }
        .fade-in-up { animation: fadeInUp 0.8s ease forwards; }
        .fade-in-up-delay-1 { animation: fadeInUp 0.8s ease 0.2s forwards; opacity: 0; }
        .fade-in-up-delay-2 { animation: fadeInUp 0.8s ease 0.4s forwards; opacity: 0; }
        .fade-in-up-delay-3 { animation: fadeInUp 0.8s ease 0.6s forwards; opacity: 0; }
        .glow { box-shadow: 0 0 60px rgba(20, 184, 166, 0.15); }
        .gradient-text {
            background: linear-gradient(135deg, #14b8a6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-gradient {
            background: radial-gradient(ellipse at 50% 0%, rgba(20, 184, 166, 0.15) 0%, transparent 70%);
        }
        @keyframes barGrow {
            from { transform: scaleY(0); }
            to { transform: scaleY(1); }
        }
        .bar-animate {
            animation: barGrow 1.5s ease forwards;
            transform-origin: bottom;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(20, 184, 166, 0.3) !important;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body style="background-color: #030712; color: white; font-family: sans-serif;">

    {{-- NAVBAR --}}
    <nav style="border-bottom: 1px solid rgba(255,255,255,0.05); position: sticky; top: 0; z-index: 50; backdrop-filter: blur(12px); background-color: rgba(3, 7, 18, 0.8);">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px; height: 64px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 28px; height: 28px; background: #14b8a6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 16px; height: 16px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span style="font-size: 18px; font-weight: 700; color: white;">Cashly</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('login') }}" style="font-size: 14px; color: #9ca3af; text-decoration: none;">Autentificare</a>
                <a href="{{ route('register') }}"
                   style="padding: 8px 16px; background: #14b8a6; color: white; font-size: 14px; font-weight: 500; border-radius: 8px; text-decoration: none;">
                    Începe gratuit
                </a>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero-gradient" style="position: relative; overflow: hidden;">
        <div style="max-width: 1152px; margin: 0 auto; padding: 96px 24px 64px; text-align: center;">
            <h1 class="fade-in-up-delay-1" style="font-size: clamp(40px, 6vw, 72px); font-weight: 700; color: white; line-height: 1.1; margin-bottom: 24px;">
                Finanțele tale,<br>
                <span class="gradient-text">sub control total</span>
            </h1>

            <p class="fade-in-up-delay-2" style="font-size: 20px; color: #9ca3af; max-width: 600px; margin: 0 auto 40px; line-height: 1.7;">
                Cashly înlocuiește foile Excel cu o platformă profesională de facturare,
                monitorizare cheltuieli și analiză financiară în timp real.
            </p>

            <div class="fade-in-up-delay-3" style="display: flex; flex-direction: column; align-items: center; gap: 16px; margin-bottom: 80px;">
                <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                    <a href="{{ route('register') }}"
                       style="padding: 16px 32px; background: #14b8a6; color: white; font-weight: 600; border-radius: 12px; text-decoration: none; font-size: 18px; box-shadow: 0 0 30px rgba(20, 184, 166, 0.4);">
                        Începe gratuit
                    </a>
                    <a href="{{ route('login') }}"
                       style="padding: 16px 32px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #d1d5db; font-weight: 600; border-radius: 12px; text-decoration: none; font-size: 18px;">
                        Ai deja cont?
                    </a>
                </div>

                {{-- Separator --}}
                <div style="display: flex; align-items: center; gap: 12px; width: 320px;">
                    <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.1);"></div>
                    <span style="font-size: 13px; color: #4b5563;">sau intră rapid cu</span>
                    <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.1);"></div>
                </div>

                {{-- Buton Google --}}
                <a href="{{ route('auth.google') }}"
                   style="display: inline-flex; align-items: center; gap: 10px; padding: 13px 28px; background: white; color: #111827; font-weight: 600; font-size: 15px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                    <svg width="20" height="20" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        <path fill="none" d="M0 0h48v48H0z"/>
                    </svg>
                    Continuă cu Google
                </a>
            </div>

            {{-- APP MOCKUP --}}
            <div class="float" style="max-width: 900px; margin: 0 auto;">
                <div class="glow" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); background: #0f172a;">

                    {{-- Browser bar --}}
                    <div style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; background: #1e293b; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444; opacity: 0.7;"></div>
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b; opacity: 0.7;"></div>
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #22c55e; opacity: 0.7;"></div>
                        <div style="flex: 1; text-align: center; font-size: 12px; color: #6b7280;">cashly.app/dashboard</div>
                    </div>

                    {{-- App content --}}
                    <div style="display: flex; height: 380px;">

                        {{-- Sidebar --}}
                        <div style="width: 180px; flex-shrink: 0; padding: 16px; background: #0f172a; border-right: 1px solid rgba(255,255,255,0.05);">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;">
                                <div style="width: 24px; height: 24px; background: #14b8a6; border-radius: 6px;"></div>
                                <span style="font-size: 14px; font-weight: 700; color: white;">Cashly</span>
                            </div>
                            @foreach([
                                ['Dashboard', true],
                                ['Clienți', false],
                                ['Produse', false],
                                ['Facturi', false],
                                ['Cheltuieli', false],
                            ] as $item)
                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; margin-bottom: 2px; background: {{ $item[1] ? 'rgba(20, 184, 166, 0.15)' : 'transparent' }};">
                                <div style="width: 12px; height: 12px; border-radius: 4px; background: {{ $item[1] ? '#14b8a6' : 'rgba(255,255,255,0.1)' }};"></div>
                                <span style="font-size: 12px; color: {{ $item[1] ? '#5eead4' : '#6b7280' }}; font-weight: {{ $item[1] ? '600' : '400' }};">{{ $item[0] }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Main content --}}
                        <div style="flex: 1; padding: 20px; overflow: hidden;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <div>
                                    <p style="font-size: 10px; color: #6b7280; margin-bottom: 4px;">Dashboard Financiar</p>
                                    <p style="font-size: 16px; font-weight: 700; color: white;">Bună ziua, Raul 👋</p>
                                </div>
                                <div style="width: 32px; height: 32px; background: #14b8a6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">R</div>
                            </div>

                            {{-- KPIs --}}
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 16px;">
                                @foreach([
                                    ['Venituri', '12.500 RON', '#14b8a6'],
                                    ['Cheltuieli', '3.200 RON', '#ef4444'],
                                    ['Profit net', '9.300 RON', '#14b8a6'],
                                    ['Restante', '2', '#f59e0b'],
                                ] as $kpi)
                                <div style="padding: 10px; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                                    <p style="font-size: 10px; color: #6b7280; margin-bottom: 4px;">{{ $kpi[0] }}</p>
                                    <p style="font-size: 13px; font-weight: 700; color: {{ $kpi[2] }};">{{ $kpi[1] }}</p>
                                </div>
                                @endforeach
                            </div>

                            {{-- Chart --}}
                            <div style="padding: 14px; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                                <p style="font-size: 10px; color: #6b7280; margin-bottom: 10px;">Cash Flow — ultimele 6 luni</p>
                                <div style="display: flex; align-items: flex-end; gap: 10px; height: 80px;">
                                    @foreach([
                                        [30, 15], [45, 20], [35, 25], [60, 15], [80, 30], [100, 20]
                                    ] as $bar)
                                    <div style="flex: 1; display: flex; align-items: flex-end; gap: 2px;">
                                        <div class="bar-animate" style="flex: 1; height: {{ $bar[0] }}%; background: rgba(20, 184, 166, 0.7); border-radius: 3px 3px 0 0;"></div>
                                        <div class="bar-animate" style="flex: 1; height: {{ $bar[1] }}%; background: rgba(239, 68, 68, 0.6); border-radius: 3px 3px 0 0;"></div>
                                    </div>
                                    @endforeach
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-top: 6px;">
                                    @foreach(['Nov', 'Dec', 'Ian', 'Feb', 'Mar', 'Apr'] as $month)
                                        <span style="font-size: 10px; color: #4b5563;">{{ $month }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section style="padding: 64px 0; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; text-align: center;">
                @foreach([
                    ['2 min', 'Timp mediu de creare factură'],
                    ['100%', 'Date izolate per utilizator'],
                    ['RON & EUR', 'Suport multi-valută'],
                ] as $stat)
                <div>
                    <p class="gradient-text" style="font-size: 40px; font-weight: 700; margin-bottom: 8px;">{{ $stat[0] }}</p>
                    <p style="color: #6b7280; font-size: 14px;">{{ $stat[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" style="padding: 96px 0;">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px;">
            <div style="text-align: center; margin-bottom: 64px;">
                <h2 style="font-size: 40px; font-weight: 700; color: white; margin-bottom: 16px;">Tot ce ai nevoie,<br>nimic în plus</h2>
                <p style="color: #9ca3af; font-size: 18px;">Construit special pentru freelanceri și PFA-uri din România</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                @foreach([
                    ['Facturare PDF', 'Creează facturi profesionale cu număr auto-generat și descarcă-le instant ca PDF.', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['Dashboard financiar', 'KPI-uri în timp real: venituri, cheltuieli, profit net și grafice cash flow lunare.', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['Gestiune clienți', 'Portofoliu complet cu statusuri automate: Activ, Prospect, Inactiv.', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['Monitorizare cheltuieli', 'Categorisește cheltuielile și vizualizează distribuția pe grafice interactive.', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['Catalog produse', 'Salvează produsele și serviciile cu prețuri în RON sau EUR pentru facturare rapidă.', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['Date securizate', 'Arhitectură multi-tenant — datele tale sunt complet izolate de ale altor utilizatori.', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                ] as $feature)
                <div class="feature-card" style="padding: 24px; border-radius: 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(20, 184, 166, 0.15); display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg style="width: 20px; height: 20px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature[2] }}"/>
                        </svg>
                    </div>
                    <h3 style="font-weight: 600; color: white; margin-bottom: 8px;">{{ $feature[0] }}</h3>
                    <p style="font-size: 14px; color: #6b7280; line-height: 1.6;">{{ $feature[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="pricing" style="padding: 96px 0; background: rgba(255,255,255,0.02);">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px;">
            <div style="text-align: center; margin-bottom: 64px;">
                <h2 style="font-size: 40px; font-weight: 700; color: white; margin-bottom: 16px;">Prețuri simple</h2>
                <p style="color: #9ca3af; font-size: 18px;">Fără costuri ascunse. Anulează oricând.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px; max-width: 700px; margin: 0 auto;">

                {{-- Basic --}}
                <div style="padding: 32px; border-radius: 20px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                    <h3 style="font-weight: 700; color: white; font-size: 20px; margin-bottom: 4px;">Basic</h3>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">Pentru freelanceri la început</p>
                    <div style="margin-bottom: 32px;">
                        <span style="font-size: 48px; font-weight: 700; color: white;">Gratuit</span>
                    </div>
                    <ul style="list-style: none; padding: 0; margin-bottom: 32px;">
                        @foreach(['Până la 5 clienți', '10 facturi/lună', 'Dashboard financiar', 'Generare PDF'] as $feature)
                        <li style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #9ca3af; margin-bottom: 12px;">
                            <svg style="width: 16px; height: 16px; color: #14b8a6; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}"
                       style="display: block; text-align: center; padding: 12px 24px; border-radius: 10px; font-weight: 500; font-size: 14px; text-decoration: none; color: #14b8a6; border: 1px solid rgba(20, 184, 166, 0.4);">
                        Începe gratuit
                    </a>
                </div>

                {{-- Pro --}}
                <div style="padding: 32px; border-radius: 20px; background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(6, 182, 212, 0.1)); border: 1px solid rgba(20, 184, 166, 0.4); position: relative;">
                    <div style="position: absolute; top: 16px; right: 16px; padding: 4px 10px; background: #14b8a6; color: white; font-size: 11px; font-weight: 700; border-radius: 999px;">
                        Popular
                    </div>
                    <h3 style="font-weight: 700; color: white; font-size: 20px; margin-bottom: 4px;">Pro</h3>
                    <p style="color: #5eead4; font-size: 14px; margin-bottom: 24px;">Pentru afaceri în creștere</p>
                    <div style="margin-bottom: 32px;">
                        <span style="font-size: 48px; font-weight: 700; color: white;">49.99</span>
                        <span style="color: #9ca3af; font-size: 16px;"> RON/lună</span>
                    </div>
                    <ul style="list-style: none; padding: 0; margin-bottom: 32px;">
                        @foreach(['Clienți nelimitați', 'Facturi nelimitate', 'Rapoarte avansate', 'Suport prioritar'] as $feature)
                        <li style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #d1d5db; margin-bottom: 12px;">
                            <svg style="width: 16px; height: 16px; color: #5eead4; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}"
                       style="display: block; text-align: center; padding: 12px 24px; background: #14b8a6; color: white; border-radius: 10px; font-weight: 500; font-size: 14px; text-decoration: none; box-shadow: 0 0 20px rgba(20, 184, 166, 0.4);">
                        Începe Pro
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section style="padding: 96px 0;">
        <div style="max-width: 700px; margin: 0 auto; padding: 0 24px; text-align: center;">
            <h2 style="font-size: 40px; font-weight: 700; color: white; margin-bottom: 24px;">Gata să îți organizezi finanțele?</h2>
            <p style="color: #9ca3af; font-size: 18px; margin-bottom: 40px;">Alătură-te freelancerilor care au renunțat la Excel.</p>
            <a href="{{ route('register') }}"
               style="display: inline-block; padding: 16px 40px; background: #14b8a6; color: white; font-weight: 600; border-radius: 12px; text-decoration: none; font-size: 18px; box-shadow: 0 0 40px rgba(20, 184, 166, 0.4);">
                Creează cont gratuit
            </a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer style="padding: 40px 0; border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 24px; height: 24px; background: #14b8a6; border-radius: 6px;"></div>
                <span style="color: white; font-weight: 700;">Cashly</span>
            </div>
            <p style="font-size: 14px; color: #4b5563;">© {{ date('Y') }} Cashly. Toate drepturile rezervate.</p>
        </div>
    </footer>

</body>
</html>
