{{-- Pagina de landing (pagina principala publica a aplicatiei) --}}
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - Platforma financiara pentru freelanceri si PFA-uri</title>
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; }
        /* overflow-x:hidden previne scroll-ul lateral pe mobil */
        body { margin:0; font-family:ui-sans-serif,system-ui,sans-serif; background:#fff; color:#0f172a; overflow-x:hidden; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .f1 { animation: fadeUp .6s ease .05s both; }
        .f2 { animation: fadeUp .6s ease .2s both; }
        .f3 { animation: fadeUp .6s ease .35s both; }

        a { text-decoration:none; }

        .btn-teal { display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:#0d9488;color:#fff;font-size:15px;font-weight:600;border-radius:10px;transition:background .2s,transform .2s,box-shadow .2s; }
        .btn-teal:hover { background:#0f766e;transform:translateY(-2px);box-shadow:0 8px 24px rgba(13,148,136,0.3); }
        .btn-outline { display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:#fff;color:#0d9488;font-size:15px;font-weight:600;border-radius:10px;border:2px solid #0d9488;transition:background .2s,transform .2s; }
        .btn-outline:hover { background:#f0fdfa;transform:translateY(-2px); }

        a.nav-link { font-size:14px;font-weight:500;color:#64748b;padding:6px 14px;border-radius:8px;transition:color .15s,background .15s; }
        a.nav-link:hover { color:#0d9488;background:#f0fdfa; }

        .benefit-card { background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px;transition:transform .25s,box-shadow .25s,border-color .25s; }
        .benefit-card:hover { transform:translateY(-4px);box-shadow:0 12px 40px rgba(13,148,136,0.1);border-color:#5eead4; }

        .feature-row { display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;padding:64px 0;border-bottom:1px solid #f1f5f9; }
        .feature-row:last-child { border-bottom:none; }

        .faq-item { border-bottom:1px solid #e2e8f0; }
        .faq-question { display:flex;align-items:center;justify-content:space-between;padding:20px 0;cursor:pointer;font-size:16px;font-weight:600;color:#0f172a; }
        .faq-answer { font-size:15px;color:#64748b;line-height:1.7;padding-bottom:20px;display:none; }
        .faq-item.open .faq-answer { display:block; }
        .faq-item.open .faq-icon { transform:rotate(45deg); }
        .faq-icon { transition:transform .2s;font-size:22px;color:#0d9488;font-weight:300; }

        .testimonial-card { background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:28px;transition:transform .2s,box-shadow .2s; }
        .testimonial-card:hover { transform:translateY(-3px);box-shadow:0 8px 32px rgba(0,0,0,0.07); }

        .pricing-card { border-radius:20px;padding:36px;transition:transform .25s,box-shadow .25s; }
        .pricing-card:hover { transform:translateY(-4px); }

        @media (max-width:900px) {
            .hero-grid { grid-template-columns:1fr !important; }
            .hero-mockup { display:none !important; }
            .benefits-grid { grid-template-columns:1fr 1fr !important; }
            .feature-row { grid-template-columns:1fr !important;gap:32px !important; }
            .pricing-grid { grid-template-columns:1fr !important; }
            .testimonials-grid { grid-template-columns:1fr !important; }
        }
        /* MOBIL: doua coloane pentru statistici, doua coloane pentru pasi */
        @media (max-width:600px) {
            .benefits-grid { grid-template-columns:1fr !important; }
            .nav-links { display:none !important; }
            /* Pasii "Cum functioneaza" - 2 coloane pe mobil */
            .steps-grid { grid-template-columns:1fr 1fr !important; }
            /* Statisticile - 2 coloane pe mobil, nu 4 */
            .stats-grid { grid-template-columns:1fr 1fr !important; gap:0 !important; }
            /* Eliminam border-right de pe toate celulele stats pe mobil */
            .stats-grid > div { border-right:none !important; border-bottom:1px solid rgba(255,255,255,0.15); padding:16px 12px !important; }
            /* Ultimele doua celule (randul 2) nu au border-bottom */
            .stats-grid > div:nth-child(3),
            .stats-grid > div:nth-child(4) { border-bottom:none; }
            /* Adaugam separator vertical in mijloc la fiecare rand */
            .stats-grid > div:nth-child(odd) { border-right:1px solid rgba(255,255,255,0.2) !important; }
            /* Ascundem linia decorativa dintre pasi pe mobil - nu mai are sens la 2 col */
            .steps-line { display:none !important; }
            /* Reducere padding sectiuni pe mobil */
            .hero-section { padding:48px 20px 0 !important; }
            .section-pad { padding:56px 20px !important; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav style="background:#fff;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:50;">
    <div style="max-width:1200px;margin:0 auto;padding:0 28px;height:64px;display:flex;align-items:center;justify-content:space-between;">
        <a href="/" style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;background:#0d9488;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span style="font-size:20px;font-weight:800;color:#0d9488;letter-spacing:-0.5px;">Cashly</span>
        </a>
        <div class="nav-links" style="display:flex;align-items:center;gap:4px;">
            <a href="#beneficii" class="nav-link">Beneficii</a>
            <a href="#cum-functioneaza" class="nav-link">Cum functioneaza</a>
            <a href="#pricing" class="nav-link">Preturi</a>
            <a href="#faq" class="nav-link">Intrebari</a>
            <div style="width:1px;height:20px;background:#e2e8f0;margin:0 8px;"></div>
            <a href="{{ route('login') }}" class="nav-link">Autentificare</a>
            <a href="{{ route('register') }}" class="btn-teal" style="padding:8px 18px;font-size:14px;margin-left:4px;">Incepe gratuit</a>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section style="background:linear-gradient(160deg,#f0fdfa 0%,#fff 60%);border-bottom:1px solid #e2e8f0;padding:80px 28px 0;">
    <div class="hero-grid" style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;padding-bottom:72px;">

        <div>
            <h1 class="f2" style="font-size:clamp(32px,4vw,52px);font-weight:800;line-height:1.15;letter-spacing:-1px;margin:0 0 22px;color:#0f172a;">
                Facturi, cheltuieli si<br>rapoarte <span style="color:#0d9488;">intr-un<br>singur loc</span>
            </h1>

            <p class="f3" style="font-size:17px;color:#374151;line-height:1.8;margin:0 0 14px;max-width:500px;">
                Cashly automatizeaza facturarea, urmarirea platilor si gestiunea cheltuielilor. Tu te ocupi de clienti, Cashly se ocupa de restul.
            </p>
            <p class="f3" style="font-size:15px;color:#64748b;line-height:1.8;margin:0 0 36px;max-width:500px;">
                Freelancerii care folosesc Cashly economisesc in medie <strong style="color:#0d9488;">6 ore pe luna</strong> si incaseaza facturile cu <strong style="color:#0d9488;">40% mai rapid</strong> datorita reminder-urilor automate.
            </p>

            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:32px;">
                <a href="{{ route('register') }}" class="btn-teal">
                    Incepe gratuit
                </a>
                <a href="{{ route('login') }}" class="btn-outline">Am deja cont</a>
            </div>

            <a href="{{ route('auth.google') }}"
               style="display:inline-flex;align-items:center;gap:9px;padding:10px 20px;background:#fff;color:#374151;font-size:14px;font-weight:500;border-radius:9px;border:1px solid #e2e8f0;transition:box-shadow .2s;"
               onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
                <svg width="17" height="17" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Continua cu Google
            </a>
        </div>

        {{-- Mockup --}}
        <div class="hero-mockup" style="border-radius:20px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 24px 80px rgba(0,0,0,0.08);transition:transform .3s,box-shadow .3s;"
             onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 32px 96px rgba(0,0,0,0.13)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 24px 80px rgba(0,0,0,0.08)'">
            <div style="display:flex;align-items:center;gap:7px;padding:10px 16px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <div style="width:10px;height:10px;border-radius:50%;background:#ef4444;opacity:.5;"></div>
                <div style="width:10px;height:10px;border-radius:50%;background:#f59e0b;opacity:.5;"></div>
                <div style="width:10px;height:10px;border-radius:50%;background:#22c55e;opacity:.5;"></div>
                <span style="flex:1;text-align:center;font-size:11px;color:#94a3b8;font-family:monospace;">cashly.app/dashboard</span>
            </div>
            <div style="display:flex;height:340px;background:#f8fafc;">
                <div style="width:160px;flex-shrink:0;background:#fff;border-right:1px solid #e2e8f0;padding:14px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
                        <div style="width:20px;height:20px;background:#0d9488;border-radius:5px;"></div>
                        <span style="font-size:13px;font-weight:700;color:#0d9488;">Cashly</span>
                    </div>
                    @foreach([['Dashboard',true],['Clienti',false],['Produse',false],['Facturi',false],['Cheltuieli',false]] as $n)
                    <div style="display:flex;align-items:center;gap:7px;padding:6px 8px;border-radius:7px;margin-bottom:2px;background:{{ $n[1]?'#f0fdfa':'transparent' }};">
                        <div style="width:6px;height:6px;border-radius:2px;background:{{ $n[1]?'#0d9488':'#cbd5e1' }};"></div>
                        <span style="font-size:11px;color:{{ $n[1]?'#0d9488':'#94a3b8' }};font-weight:{{ $n[1]?'600':'400' }};">{{ $n[0] }}</span>
                    </div>
                    @endforeach
                </div>
                <div style="flex:1;padding:16px;overflow:hidden;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                        <div>
                            <p style="font-size:10px;color:#94a3b8;margin:0 0 2px;">Buna ziua</p>
                            <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Dashboard Financiar</p>
                        </div>
                        <div style="width:28px;height:28px;background:#0d9488;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;">R</div>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:12px;">
                        @foreach([['Venituri','12.500 RON','#059669'],['Cheltuieli','3.200 RON','#ef4444'],['Profit','9.300 RON','#0d9488'],['Restante','2','#f59e0b']] as $k)
                        <div style="padding:9px;border-radius:9px;background:#fff;border:1px solid #f1f5f9;">
                            <p style="font-size:9px;color:#94a3b8;margin:0 0 3px;">{{ $k[0] }}</p>
                            <p style="font-size:11px;font-weight:700;color:{{ $k[2] }};margin:0;">{{ $k[1] }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div style="padding:12px;border-radius:10px;background:#fff;border:1px solid #f1f5f9;">
                        <p style="font-size:9px;color:#94a3b8;margin:0 0 8px;">Cash Flow, 6 luni</p>
                        <div style="display:flex;align-items:flex-end;gap:6px;height:70px;">
                            @foreach([[30,15],[45,20],[35,25],[60,15],[80,30],[100,20]] as $b)
                            <div style="flex:1;display:flex;align-items:flex-end;gap:2px;">
                                <div style="flex:1;height:{{ $b[0] }}%;background:#5eead4;border-radius:2px 2px 0 0;"></div>
                                <div style="flex:1;height:{{ $b[1] }}%;background:#fca5a5;border-radius:2px 2px 0 0;"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS - MOBIL: 2x2 grid; DESKTOP: 4 coloane --}}
<section style="background:#0d9488;padding:40px 28px;">
    <div class="stats-grid" style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:0;text-align:center;">
        @foreach([
            ['6 ore','economisite in medie pe luna'],
            ['40%','facturi incasate mai rapid'],
            ['2 minute','de la comanda la factura trimisa'],
            ['0 lei','cost sa incepi, fara card'],
        ] as $s)
        {{-- MOBIL: padding mai mic si fara border-right pe ultimul din rand --}}
        <div style="padding:12px 16px;{{ !$loop->last ? 'border-right:1px solid rgba(255,255,255,0.2);' : '' }}">
            <p style="font-size:28px;font-weight:800;color:#fff;margin:0 0 4px;transition:transform .2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform=''">{{ $s[0] }}</p>
            <p style="font-size:13px;color:#99f6e4;margin:0;">{{ $s[1] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- BENEFICII --}}
<section id="beneficii" style="max-width:1200px;margin:0 auto;padding:80px 28px;">
    <div style="text-align:center;margin-bottom:56px;">
        <p style="font-size:13px;font-weight:600;color:#0d9488;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">De ce Cashly</p>
        <h2 style="font-size:clamp(28px,3vw,42px);font-weight:800;color:#0f172a;margin:0 0 14px;letter-spacing:-0.5px;">Controlul complet al afacerii tale,<br>automatizat de la inceput pana la sfarsit</h2>
        <p style="font-size:16px;color:#64748b;margin:0 auto;max-width:600px;line-height:1.7;">Cashly nu este doar o aplicatie de facturare. Este un sistem integrat care gestioneaza intreaga activitate financiara a firmei tale, exact cum o face un ERP, dar construit special pentru freelanceri si PFA-uri.</p>
    </div>

    <div class="benefits-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
        @foreach([
            ['#f0fdfa','#0d9488','M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','Facturare complet automatizata','Factura generata, numerotata si trimisa pe email fara sa deschizi alt program. Clientul primeste PDF-ul profesional in secunde.'],
            ['#fef3c7','#d97706','M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9','Reminder-uri automate de plata','Sistemul trimite automat notificari clientilor cu facturi scadente sau restante. Tu nu mai dai telefoane stanjenitoare, banii vin singuri.'],
            ['#ede9fe','#7c3aed','M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','Rapoarte financiare in timp real','Dashboard actualizat la fiecare tranzactie. Stii instantaneu cat ai incasat, cat ai cheltuit si care e profitul net, fara calcule manuale.'],
            ['#f0fdf4','#16a34a','M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','Export Excel pentru contabil','Cu un singur click exporti toate cheltuielile si veniturile in format Excel. Trimiti fisierul contabilului si gata, nu mai pierzi ore cu strans documente.'],
        ] as $b)
        <div class="benefit-card">
            <div style="width:44px;height:44px;background:{{ $b[0] }};border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <svg width="22" height="22" fill="none" stroke="{{ $b[1] }}" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $b[2] }}"/>
                </svg>
            </div>
            <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 8px;">{{ $b[3] }}</h3>
            <p style="font-size:13px;color:#64748b;line-height:1.65;margin:0;">{{ $b[4] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- CUM FUNCTIONEAZA --}}
<section id="cum-functioneaza" style="background:#f8fafc;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding:80px 28px;">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:56px;">
            <p style="font-size:13px;font-weight:600;color:#0d9488;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">Cum functioneaza</p>
            <h2 style="font-size:clamp(26px,3vw,40px);font-weight:800;color:#0f172a;margin:0 0 14px;letter-spacing:-0.5px;">De la client nou la bani incasati,<br>totul se intampla in Cashly</h2>
            <p style="font-size:16px;color:#64748b;margin:0 auto;max-width:540px;line-height:1.7;">Fluxul complet de lucru, de la adaugarea clientului pana la incasarea facturii, automatizat pas cu pas.</p>
        </div>

        {{-- MOBIL: 2x2 grid; DESKTOP: 4 coloane cu linie decorativa intre pasi --}}
        <div class="steps-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;position:relative;">
            <div class="steps-line" style="position:absolute;top:28px;left:12%;right:12%;height:2px;background:linear-gradient(90deg,#0d9488,#0891b2);z-index:0;"></div>
            @foreach([
                ['1','Adaugi clientul o singura data','Numele, emailul si datele de facturare se salveaza. Toate facturile viitoare se completeaza automat.'],
                ['2','Creezi factura in 2 minute','Selectezi clientul si serviciile din catalog. Cashly completeaza tot automat si genereaza PDF-ul.'],
                ['3','Cashly trimite factura','PDF-ul ajunge pe emailul clientului direct din platforma. Numarul de factura se incrementeaza singur.'],
                ['4','Incasezi si arhivezi automat','Marchezi factura ca platita. Dashboard-ul se actualizeaza instant, documentul e arhivat si regasibil oricand.'],
            ] as $step)
            <div style="text-align:center;padding:0 12px;position:relative;z-index:1;">
                <div style="width:56px;height:56px;background:#0d9488;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:20px;font-weight:800;color:#fff;border:4px solid #f8fafc;">{{ $step[0] }}</div>
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 8px;line-height:1.4;">{{ $step[1] }}</h3>
                <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURE ROWS --}}
<section style="max-width:1100px;margin:0 auto;padding:80px 28px;">
    <div style="text-align:center;margin-bottom:64px;">
        <p style="font-size:13px;font-weight:600;color:#0d9488;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">Functionalitati cheie</p>
        <h2 style="font-size:clamp(26px,3vw,40px);font-weight:800;color:#0f172a;margin:0;letter-spacing:-0.5px;">Tot ce face Cashly pentru tine</h2>
    </div>

    {{-- Feature 1: Facturare --}}
    <div class="feature-row">
        <div>
            <div style="width:48px;height:48px;background:#f0fdfa;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <svg width="24" height="24" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 style="font-size:22px;font-weight:800;color:#0f172a;margin:0 0 14px;letter-spacing:-0.3px;">Sistem de facturare cu numerotare automata</h3>
            <p style="font-size:15px;color:#64748b;line-height:1.8;margin:0 0 24px;">Cel mai mare consumator de timp pentru un freelancer este facturarea manuala. Cashly elimina acest proces complet. Introduci serviciile o singura data in catalog, cu pretul in RON sau EUR. La urmatoarea factura, le selectezi dintr-o lista si campurile se completeaza automat. Numarul de factura urmator din serie e calculat si aplicat automat, fara sa numeri tu nimic.</p>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                @foreach(['Numerotare automata serie/an','Autofill din catalogul de servicii','Suport RON si EUR','Trimitere directa pe email','Descarcare PDF instant'] as $point)
                <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:#374151;">
                    <svg width="16" height="16" fill="none" stroke="#0d9488" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $point }}
                </li>
                @endforeach
            </ul>
        </div>
        {{-- Mockup factura --}}
        <div style="background:#f0fdfa;border-radius:20px;padding:28px;min-height:280px;display:flex;align-items:center;justify-content:center;align-self:center;transition:transform .3s,box-shadow .3s;"
             onmouseover="this.style.transform='translateY(-6px) scale(1.02)';this.style.boxShadow='0 24px 64px rgba(13,148,136,0.15)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 4px 24px rgba(13,148,136,0.1);width:100%;max-width:320px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                    <div>
                        <div style="font-size:12px;font-weight:800;color:#0d9488;">CASHLY</div>
                        <div style="font-size:10px;color:#94a3b8;margin-top:1px;">Factura Fiscala</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:11px;font-weight:700;color:#0f172a;">#2024-047</div>
                        <div style="font-size:10px;color:#94a3b8;">15.05.2024</div>
                        <span style="display:inline-block;margin-top:4px;padding:2px 10px;background:#dcfce7;color:#16a34a;font-size:10px;font-weight:600;border-radius:20px;">Trimisa</span>
                    </div>
                </div>
                <div style="padding:10px 0;border-top:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9;margin-bottom:14px;">
                    <div style="font-size:9px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.05em;">Catre</div>
                    <div style="font-size:12px;font-weight:600;color:#0f172a;">ABC Solutions SRL</div>
                    <div style="font-size:10px;color:#64748b;">contact@abc-solutions.ro</div>
                </div>
                <div style="margin-bottom:14px;display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:11px;font-weight:500;color:#374151;">Servicii web design</div>
                            <div style="font-size:10px;color:#94a3b8;">1 x 2.500 RON</div>
                        </div>
                        <div style="font-size:11px;font-weight:600;color:#0f172a;">2.500 RON</div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:11px;font-weight:500;color:#374151;">Mentenanta lunara</div>
                            <div style="font-size:10px;color:#94a3b8;">1 x 500 RON</div>
                        </div>
                        <div style="font-size:11px;font-weight:600;color:#0f172a;">500 RON</div>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:2px solid #f0fdfa;">
                    <div style="font-size:12px;font-weight:700;color:#0f172a;">TOTAL</div>
                    <div style="font-size:18px;font-weight:800;color:#0d9488;">3.000 RON</div>
                </div>
                <div style="margin-top:14px;padding:10px;background:#f0fdfa;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:6px;">
                    <svg width="13" height="13" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span style="font-size:11px;font-weight:600;color:#0d9488;">Trimite pe email</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Feature 2: Reminder-uri --}}
    <div class="feature-row">
        {{-- Mockup notificari --}}
        <div style="background:#fff7ed;border-radius:20px;padding:28px;min-height:280px;display:flex;align-items:center;justify-content:center;align-self:center;transition:transform .3s,box-shadow .3s;"
             onmouseover="this.style.transform='translateY(-6px) scale(1.02)';this.style.boxShadow='0 24px 64px rgba(217,119,6,0.15)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="width:100%;max-width:320px;display:flex;flex-direction:column;gap:10px;">
                <div style="font-size:11px;font-weight:700;color:#92400e;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.05em;">Reminder-uri active</div>
                @foreach([
                    ['XYZ Creative SRL','#2024-043 · 1.200 RON','Scadent azi','#fef3c7','#d97706'],
                    ['Popescu Ion PFA','#2024-041 · 800 RON','Restanta 5 zile','#fef2f2','#ef4444'],
                    ['Tech Startup SRL','#2024-039 · 3.500 RON','Platita','#f0fdf4','#16a34a'],
                ] as $r)
                <div style="background:#fff;border-radius:10px;padding:12px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <div style="width:34px;height:34px;min-width:34px;background:{{ $r[3] }};border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <svg width="16" height="16" fill="none" stroke="{{ $r[4] }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:11px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $r[0] }}</div>
                        <div style="font-size:10px;color:#94a3b8;">{{ $r[1] }}</div>
                    </div>
                    <span style="padding:3px 9px;background:{{ $r[3] }};color:{{ $r[4] }};font-size:9px;font-weight:700;border-radius:20px;white-space:nowrap;">{{ $r[2] }}</span>
                </div>
                @endforeach
                <div style="background:#fff;border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:8px;border:1px dashed #fed7aa;">
                    <svg width="14" height="14" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:10px;color:#d97706;font-weight:500;">Urmator reminder in 2 zile · automat</span>
                </div>
            </div>
        </div>
        <div>
            <div style="width:48px;height:48px;background:#fff7ed;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <svg width="24" height="24" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 style="font-size:22px;font-weight:800;color:#0f172a;margin:0 0 14px;letter-spacing:-0.3px;">Reminder-uri automate pentru facturi neplatite</h3>
            <p style="font-size:15px;color:#64748b;line-height:1.8;margin:0 0 24px;">Unul din trei freelanceri pierde bani din cauza facturilor uitate sau platite cu intarziere. Cashly monitorizeaza continuu statusul fiecarei facturi si trimite automat emailuri de reminder clientilor cu plati restante. Nu mai urmaresti manual cine a platit si cine nu. Sistemul se ocupa, tu incasezi la timp.</p>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                @foreach(['Reminder-uri la scadenta si dupa','Emailuri personalizate cu tonul firmei tale','Marcarea automata a facturilor restante','Notificari in dashboard pentru situatii critice'] as $point)
                <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:#374151;">
                    <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $point }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Feature 3: Cheltuieli --}}
    <div class="feature-row">
        <div>
            <div style="width:48px;height:48px;background:#f0fdf4;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <svg width="24" height="24" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 style="font-size:22px;font-weight:800;color:#0f172a;margin:0 0 14px;letter-spacing:-0.3px;">Gestiunea cheltuielilor cu export pentru contabil</h3>
            <p style="font-size:15px;color:#64748b;line-height:1.8;margin:0 0 24px;">Inregistrezi fiecare cheltuiala pe categorie, atasezi bonul foto si adaugi o descriere scurta. La final de luna sau trimestru, exporti tot intr-un fisier Excel structurat, gata de trimis contabilului. Nu mai cauti bonuri prin sertare, nu mai rescrii date in foi de calcul. Cashly tine evidenta completa a cheltuielilor firmei tale.</p>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                @foreach(['Categorii predefinite si personalizabile','Bon foto atasat la fiecare cheltuiala','Export Excel complet pentru contabil','Vizualizare cheltuieli pe categorii si perioade'] as $point)
                <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:#374151;">
                    <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $point }}
                </li>
                @endforeach
            </ul>
        </div>
        {{-- Mockup cheltuieli --}}
        <div style="background:#f0fdf4;border-radius:20px;padding:28px;min-height:280px;display:flex;align-items:center;justify-content:center;align-self:center;transition:transform .3s,box-shadow .3s;"
             onmouseover="this.style.transform='translateY(-6px) scale(1.02)';this.style.boxShadow='0 24px 64px rgba(22,163,74,0.15)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="width:100%;max-width:320px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <div style="font-size:11px;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:0.05em;">Cheltuieli Mai 2024</div>
                    <div style="padding:5px 12px;background:#16a34a;color:#fff;font-size:10px;font-weight:600;border-radius:7px;display:flex;align-items:center;gap:5px;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </div>
                </div>
                <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
                    @foreach([
                        ['Software & tools','Adobe CC, Figma','450 RON','#ede9fe','#7c3aed'],
                        ['Transport','Uber, CFR','120 RON','#fef3c7','#d97706'],
                        ['Echipamente','Tastatura mecanica','380 RON','#dbeafe','#2563eb'],
                        ['Marketing','Google Ads','200 RON','#fce7f3','#db2777'],
                    ] as $exp)
                    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-bottom:1px solid #f0fdf4;">
                        <div style="width:30px;height:30px;min-width:30px;background:{{ $exp[3] }};border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <div style="width:8px;height:8px;border-radius:2px;background:{{ $exp[4] }};"></div>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:11px;font-weight:600;color:#0f172a;">{{ $exp[0] }}</div>
                            <div style="font-size:10px;color:#94a3b8;">{{ $exp[1] }}</div>
                        </div>
                        <div style="font-size:12px;font-weight:700;color:#374151;">{{ $exp[2] }}</div>
                    </div>
                    @endforeach
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#f0fdf4;">
                        <div style="font-size:11px;font-weight:700;color:#166534;">Total luna</div>
                        <div style="font-size:14px;font-weight:800;color:#16a34a;">1.150 RON</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TESTIMONIALE --}}
<section style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:80px 28px;">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:52px;">
            <p style="font-size:13px;font-weight:600;color:#0d9488;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">Ce spun utilizatorii</p>
            <h2 style="font-size:clamp(26px,3vw,38px);font-weight:800;color:#0f172a;margin:0;letter-spacing:-0.5px;">Freelanceri care au ales sa lucreze mai smart</h2>
        </div>

        <div class="testimonials-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
            @foreach([
                ['Andrei M.','Web Developer, Freelancer','Inainte petreceam aproape o zi pe luna cu facturile. Acum le trimit in 2 minute si am uitat complet ca exista facturi neplatite, le urmareste Cashly automat.','AM'],
                ['Diana P.','Designer UX, PFA','Cel mai util lucru este exportul pentru contabil. Trimit un fisier Excel si gata, nu mai stau sa caut bonuri si chitante. Mi-a economisit ore intregi la fiecare trimestru.','DP'],
                ['Mihai R.','Consultant IT, SRL','Aveam clienti care uitau sa plateasca si mi-era jena sa sun. Acum Cashly trimite reminder-ul automat si incasez cu doua saptamani mai devreme decat inainte.','MR'],
            ] as $t)
            <div class="testimonial-card">
                <div style="display:flex;gap:4px;margin-bottom:16px;">
                    @for($i=0;$i<5;$i++)
                    <svg width="16" height="16" fill="#f59e0b" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <p style="font-size:14px;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">"{{ $t[2] }}"</p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;min-width:40px;background:#0d9488;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;">{{ $t[3] }}</div>
                    <div>
                        <p style="font-size:14px;font-weight:600;color:#0f172a;margin:0;">{{ $t[0] }}</p>
                        <p style="font-size:12px;color:#94a3b8;margin:0;">{{ $t[1] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING --}}
<section id="pricing" style="padding:80px 28px;">
    <div style="max-width:860px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:52px;">
            <p style="font-size:13px;font-weight:600;color:#0d9488;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">Preturi</p>
            <h2 style="font-size:clamp(26px,3vw,40px);font-weight:800;color:#0f172a;margin:0 0 12px;letter-spacing:-0.5px;">Simplu si transparent</h2>
            <p style="font-size:16px;color:#64748b;margin:0;">Fara costuri ascunse. Fara contract pe termen lung. Anulezi oricand.</p>
        </div>

        <div style="max-width:480px;margin:0 auto;">
            <div class="pricing-card" style="background:linear-gradient(160deg,#0d9488 0%,#0891b2 100%);position:relative;display:flex;flex-direction:column;border-radius:1.25rem;overflow:hidden;">
                <div style="position:absolute;top:-1px;left:50%;transform:translateX(-50%);padding:4px 16px;background:#f59e0b;color:#fff;font-size:11px;font-weight:700;border-radius:0 0 10px 10px;white-space:nowrap;letter-spacing:0.04em;">PRIMA LUNĂ GRATUITĂ</div>
                <div style="padding:2rem 2rem 1.5rem;">
                    <h3 style="font-size:20px;font-weight:700;color:#fff;margin:0.5rem 0 4px;">Plan Pro</h3>
                    <p style="font-size:13px;color:rgba(255,255,255,0.7);margin:0 0 20px;">Acces complet. Fara restricții.</p>
                    <div style="padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.2);">
                        <div style="display:flex;align-items:flex-end;gap:6px;">
                            <span style="font-size:52px;font-weight:800;color:#fff;line-height:1;">19<span style="font-size:32px;">,99</span></span>
                            <div style="margin-bottom:8px;">
                                <span style="font-size:16px;font-weight:600;color:rgba(255,255,255,0.85);">RON</span>
                                <span style="font-size:13px;color:rgba(255,255,255,0.6);display:block;">/lună</span>
                            </div>
                        </div>
                        <p style="font-size:13px;color:rgba(255,255,255,0.65);margin:8px 0 0;">Prima lună gratuită. Anulezi oricând, fără penalități.</p>
                    </div>
                    <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:11px;">
                        @foreach(['Facturi nelimitate cu PDF și email','Gestionare clienți și produse','Tracker cheltuieli cu upload bonuri','Rapoarte financiare și TVA','Export CSV / Excel','Notificări automate restanțe'] as $f)
                        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:rgba(255,255,255,0.9);">
                            <svg width="16" height="16" fill="none" stroke="#5eead4" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}" style="display:block;text-align:center;padding:14px;border-radius:10px;font-size:15px;font-weight:600;color:#0d9488;background:#fff;transition:opacity .2s;" onmouseover="this.style.opacity='0.92'" onmouseout="this.style.opacity='1'">
                        Începe luna gratuită
                    </a>
                    <p style="text-align:center;font-size:12px;color:rgba(255,255,255,0.5);margin:12px 0 0;">Plată securizată prin Stripe. Fără contract.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section id="faq" style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:80px 28px;">
    <div style="max-width:720px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:48px;">
            <p style="font-size:13px;font-weight:600;color:#0d9488;text-transform:uppercase;letter-spacing:1px;margin:0 0 10px;">Intrebari frecvente</p>
            <h2 style="font-size:clamp(24px,3vw,36px);font-weight:800;color:#0f172a;margin:0;letter-spacing:-0.5px;">Ai intrebari? Avem raspunsuri.</h2>
        </div>

        @foreach([
            ['Cat costa Cashly?','Cashly costa 19,99 RON pe luna, dupa o prima luna complet gratuita, fara card de credit la inregistrare. Primesti acces la toate functiile: facturi nelimitate, clienti, cheltuieli, rapoarte si export Excel. Poti anula oricand, fara penalitati.'],
            ['Cum functioneaza reminder-urile automate?','Dupa ce creezi o factura si setezi scadenta, Cashly trimite automat un email de reminder clientului cu cateva zile inainte de scadenta si apoi periodic dupa ce factura a expirat. Tu nu faci nimic, sistemul se ocupa complet.'],
            ['Pot exporta datele pentru contabilul meu?','Da, cu un singur click exporti toate cheltuielile si veniturile intr-un fisier Excel structurat, gata de trimis contabilului. Exportul include categorii, date, sume si toate detaliile necesare.'],
            ['Datele mele sunt in siguranta?','Absolut. Fiecare cont Cashly este complet izolat de celelalte. Datele tale nu sunt accesibile niciodata altor utilizatori. Conexiunea este criptata, la fel ca la aplicatiile de online banking, si datele sunt salvate automat zilnic.'],
            ['Pot folosi Cashly daca am un SRL, nu doar PFA?','Da, Cashly functioneaza pentru orice forma de organizare: PFA, II, SRL, freelancer fara forma legala. Introduci datele firmei tale in setari si acestea apar automat pe toate facturile generate.'],
            ['Ce se intampla daca depasesc limita planului gratuit?','Vei fi notificat cand te apropii de limita. Poti face upgrade la planul Pro oricand, din sectiunea Setari, fara sa pierzi niciun dato sau document.'],
        ] as $faq)
        <div class="faq-item" onclick="this.classList.toggle('open')">
            <div class="faq-question">
                <span>{{ $faq[0] }}</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">{{ $faq[1] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA FINAL --}}
<section style="padding:80px 28px;background:#fff;">
    <div style="max-width:700px;margin:0 auto;text-align:center;">
        <h2 style="font-size:clamp(28px,3vw,42px);font-weight:800;color:#0f172a;margin:0 0 16px;letter-spacing:-0.5px;">Incepe sa automatizezi<br>administratia firmei tale.</h2>
        <p style="font-size:16px;color:#64748b;margin:0 0 8px;line-height:1.7;">Cont creat in 30 de secunde. Prima factura trimisa in mai putin de 2 minute.</p>
        <p style="font-size:15px;color:#94a3b8;margin:0 0 36px;">Fara card de credit la inregistrare. Prima luna gratuita, apoi 19,99 RON/luna.</p>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;">
            <a href="{{ route('register') }}" class="btn-teal" style="font-size:16px;padding:14px 36px;">Creeaza cont gratuit</a>
            <a href="{{ route('auth.google') }}"
               style="display:inline-flex;align-items:center;gap:9px;padding:14px 24px;background:#fff;color:#374151;font-size:15px;font-weight:500;border-radius:10px;border:1px solid #e2e8f0;transition:box-shadow .2s;"
               onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
                <svg width="17" height="17" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Continua cu Google
            </a>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:28px;">
    <div style="max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:9px;">
            <div style="width:26px;height:26px;background:#0d9488;border-radius:7px;"></div>
            <span style="font-weight:800;color:#0d9488;font-size:16px;">Cashly</span>
        </div>
        <p style="font-size:13px;color:#94a3b8;margin:0;">© {{ date('Y') }} Cashly. Toate drepturile rezervate.</p>
    </div>
</footer>

<script>
    // FAQ toggle
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.faq-item').forEach(other => {
                if (other !== item) other.classList.remove('open');
            });
        });
    });

    // Smooth scroll fara hash in URL
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>

</body>
</html>
