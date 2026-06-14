@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - {{ $title ?? 'Dashboard' }}</title>
    <link rel="icon" type="image/png" href="/logo.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50">

<div id="sidebar-overlay"
     class="fixed inset-0 z-20 bg-black bg-opacity-50 hidden md:hidden"
     onclick="inchideMeniu()">
</div>

<div class="flex h-screen overflow-hidden">

    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 bg-white border-r border-gray-200
                  -translate-x-full transition-transform duration-300 ease-in-out
                  md:relative md:translate-x-0">

        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 flex-shrink-0">
            <a href="{{ url('/') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <img src="/logo.png" alt="Cashly" class="h-8 w-auto">
                <span class="text-xl font-bold text-teal-600">Cashly</span>
            </a>

            <button onclick="inchideMeniu()"
                    class="p-1 rounded-lg text-gray-400 hover:bg-gray-100 md:hidden"
                    aria-label="Inchide meniu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

            <a href="{{ route('dashboard') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('clients.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('clients.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Clienți
            </a>

            <a href="{{ route('products.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('products.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Produse
            </a>

            <a href="{{ route('invoices.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('invoices.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Facturi
            </a>

            <a href="{{ route('expenses.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('expenses.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Cheltuieli
            </a>

            <a href="{{ route('reports.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('reports.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Rapoarte
            </a>
        </nav>

        <div class="px-4 py-4 space-y-1 border-t border-gray-200 flex-shrink-0">

            <a href="{{ route('subscription.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('subscription.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Abonament
            </a>

            <a href="{{ route('settings.index') }}"
               onclick="inchideMeniu()"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
               {{ request()->routeIs('settings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Setări
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center w-full gap-3 px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Deconectare
                </button>
            </form>
        </div>

    </aside>

    <div class="flex flex-col flex-1 overflow-hidden min-w-0">

        <header class="flex items-center h-16 px-4 md:px-6 bg-white border-b border-gray-200 gap-3 flex-shrink-0">

            <button onclick="deschideMeniu()"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 md:hidden flex-shrink-0"
                    aria-label="Deschide meniu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-lg font-semibold text-gray-800 flex-1 hidden md:block">{{ $title ?? 'Dashboard' }}</h1>

            <h1 class="text-base font-semibold text-gray-800 flex-1 md:hidden truncate">{{ $title ?? 'Dashboard' }}</h1>

            <div class="relative flex-shrink-0" id="profile-menu-wrapper">
                <button onclick="toggleProfileMenu()" id="profile-menu-btn"
                        class="flex items-center gap-2 min-w-0 px-2 md:px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">

                    @if(auth()->user()->avatar && Storage::disk('public')->exists(auth()->user()->avatar))
                        <img src="{{ Storage::disk('public')->url(auth()->user()->avatar) }}" alt="Avatar"
                             class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                    @else
                        <div class="flex items-center justify-center w-8 h-8 bg-teal-600 rounded-full flex-shrink-0">
                            <span class="text-sm font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif

                    <div class="min-w-0 text-left hidden md:block">
                        <p class="text-sm font-medium text-gray-700 leading-tight truncate max-w-32">
                            {{ auth()->user()->company_name ?? auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-400 leading-tight truncate max-w-32">{{ auth()->user()->email }}</p>
                    </div>

                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="profile-menu"
                     style="display:none;position:absolute;right:0;top:calc(100% + 8px);width:240px;background:#fff;border:1px solid #e5e7eb;border-radius:0.75rem;box-shadow:0 10px 40px rgba(0,0,0,0.12);z-index:100;overflow:hidden;">

                    <div style="padding:1rem;border-bottom:1px solid #f3f4f6;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            @if(auth()->user()->avatar && Storage::disk('public')->exists(auth()->user()->avatar))
                                <img src="{{ Storage::disk('public')->url(auth()->user()->avatar) }}" alt="Avatar"
                                     style="width:40px;height:40px;min-width:40px;border-radius:50%;object-fit:cover;border:1px solid #e5e7eb;">
                            @else
                                <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:#0d9488;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <span style="color:#fff;font-size:1rem;font-weight:700;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div style="min-width:0;">
                                <p style="font-size:0.875rem;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ auth()->user()->company_name ?? auth()->user()->name }}
                                </p>
                                <p style="font-size:0.75rem;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="padding:0.5rem;">
                        <a href="{{ route('profile.edit') }}"
                           style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem 0.75rem;border-radius:0.5rem;font-size:0.875rem;color:#374151;text-decoration:none;"
                           onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profilul meu
                        </a>
                        <a href="{{ route('settings.index') }}"
                           style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem 0.75rem;border-radius:0.5rem;font-size:0.875rem;color:#374151;text-decoration:none;"
                           onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Setări
                        </a>
                    </div>

                    <div style="padding:0.5rem;border-top:1px solid #f3f4f6;">
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit"
                                    style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem 0.75rem;border-radius:0.5rem;font-size:0.875rem;color:#ef4444;background:transparent;border:none;cursor:pointer;width:100%;"
                                    onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Deconectare
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </header>

        @php $authUser = auth()->user(); @endphp

        @if($authUser && $authUser->isOnTrial() && $authUser->trialDaysLeft() <= 7)
            <div style="background:#fffbeb;border-bottom:1px solid #fcd34d;padding:0.5rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <p style="font-size:0.8125rem;color:#92400e;">
                    ⏳ Mai ai <strong>{{ $authUser->trialDaysLeft() }} zile</strong> din perioada gratuită.
                </p>
                <a href="{{ route('subscription.index') }}"
                   style="font-size:0.8125rem;font-weight:600;color:#b45309;white-space:nowrap;text-decoration:underline;">
                    Abonează-te acum
                </a>
            </div>
        @elseif($authUser && !$authUser->hasActiveSubscription())
            <div style="background:#fef2f2;border-bottom:1px solid #fca5a5;padding:0.5rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <p style="font-size:0.8125rem;color:#991b1b;">
                    🔒 Trial-ul a expirat. Abonează-te pentru a continua să folosești Cashly.
                </p>
                <a href="{{ route('subscription.index') }}"
                   style="font-size:0.8125rem;font-weight:600;color:#dc2626;white-space:nowrap;text-decoration:underline;">
                    Abonează-te
                </a>
            </div>
        @endif

        @if($authUser && !$authUser->company_name)
            <div style="background:#eff6ff;border-bottom:1px solid #bfdbfe;padding:0.5rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <svg width="15" height="15" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-size:0.8125rem;color:#1e40af;">Completează datele firmei tale pentru ca facturile să fie valabile legal.</p>
                </div>
                <a href="{{ route('settings.index') }}#firma"
                   style="font-size:0.8125rem;font-weight:600;color:#2563eb;white-space:nowrap;text-decoration:underline;">
                    Completează acum
                </a>
            </div>
        @endif

        <main class="flex-1 p-4 md:p-6 overflow-y-auto">

            @if(session('success'))
                <div class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 mb-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>

    </div>

</div>

<script>
    // Deschide sidebar-ul pe mobil
    // Elimina clasa -translate-x-full si arata overlay-ul
    function deschideMeniu() {
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebar-overlay');

        // Mutam sidebar-ul in pozitia vizibila
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');

        // Aratam fundalul intunecat
        overlay.classList.remove('hidden');

        // Blocam scroll-ul pe body cat timp meniul e deschis
        document.body.style.overflow = 'hidden';
    }

    // Inchide sidebar-ul pe mobil
    // Adauga clasa -translate-x-full si ascunde overlay-ul
    function inchideMeniu() {
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebar-overlay');

        // Trimitem sidebar-ul inapoi in afara ecranului
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');

        // Ascundem fundalul intunecat
        overlay.classList.add('hidden');

        // Deblocam scroll-ul pe body
        document.body.style.overflow = '';
    }

    // Deschide sau inchide dropdown-ul de profil din header
    function toggleProfileMenu() {
        var menu = document.getElementById('profile-menu');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    // Inchide dropdown-ul de profil cand se da click in afara lui
    document.addEventListener('click', function(e) {
        var wrapper = document.getElementById('profile-menu-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('profile-menu').style.display = 'none';
        }
    });

    // Inchide meniul mobil la redimensionarea ferestrei pe desktop
    // (evitam situatia in care meniul ramane deschis dupa rotirea telefonului)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            inchideMeniu();
        }
    });
</script>

</body>
</html>
