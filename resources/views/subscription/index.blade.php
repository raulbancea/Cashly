<x-cashly-layout>
    <x-slot name="title">Abonament</x-slot>

    <div class="max-w-md py-4 mx-auto">

        {{-- Status curent --}}
        @if($user->isOnTrial())
            @php $urgent = $user->trialDaysLeft() <= 7; @endphp
            <div style="padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:0.875rem;border:1px solid {{ $urgent ? '#fcd34d' : '#6ee7b7' }};background:{{ $urgent ? '#fffbeb' : '#f0fdf4' }};display:flex;align-items:center;gap:0.875rem;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:{{ $urgent ? '#fef3c7' : '#dcfce7' }};display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="{{ $urgent ? '#d97706' : '#16a34a' }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:{{ $urgent ? '#92400e' : '#166534' }};margin:0 0 2px;">Perioadă de trial gratuită</p>
                    <p style="font-size:0.8125rem;color:{{ $urgent ? '#b45309' : '#15803d' }};margin:0;">
                        @if($user->trialDaysLeft() > 0)
                            Mai ai <strong>{{ $user->trialDaysLeft() }} {{ $user->trialDaysLeft() === 1 ? 'zi' : 'zile' }}</strong> din luna gratuită.
                        @else
                            Trial-ul expiră astăzi.
                        @endif
                    </p>
                </div>
            </div>

        @elseif($user->subscription_status === 'active')
            <div style="padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:0.875rem;border:1px solid #6ee7b7;background:#f0fdf4;display:flex;align-items:center;gap:0.875rem;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:#166534;margin:0 0 2px;">Abonament activ</p>
                    <p style="font-size:0.8125rem;color:#15803d;margin:0;">
                        Următoarea plată pe <strong>{{ $user->subscription_ends_at?->format('d.m.Y') }}</strong>.
                    </p>
                </div>
            </div>

        @elseif($user->subscription_status === 'canceled')
            <div style="padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:0.875rem;border:1px solid #fca5a5;background:#fef2f2;display:flex;align-items:center;gap:0.875rem;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:#991b1b;margin:0 0 2px;">Abonament anulat</p>
                    <p style="font-size:0.8125rem;color:#b91c1c;margin:0;">Abonează-te din nou pentru a continua.</p>
                </div>
            </div>

        @else
            <div style="padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:0.875rem;border:1px solid #fca5a5;background:#fef2f2;display:flex;align-items:center;gap:0.875rem;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:#991b1b;margin:0 0 2px;">Trial expirat</p>
                    <p style="font-size:0.8125rem;color:#b91c1c;margin:0;">Luna gratuită a luat sfârșit. Alege un plan pentru a continua.</p>
                </div>
            </div>
        @endif

        {{-- Card plan --}}
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:1rem;overflow:hidden;box-shadow:0 1px 8px rgba(0,0,0,0.06);">

            {{-- Header card --}}
            <div style="padding:1.75rem 1.75rem 1.5rem;background:linear-gradient(135deg,#0d9488 0%,#0891b2 100%);">
                <p style="font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.5rem;">Plan Pro</p>
                <div style="display:flex;align-items:flex-end;gap:0.25rem;">
                    <span style="font-size:2.5rem;font-weight:800;color:#fff;line-height:1;">19,99</span>
                    <span style="font-size:1rem;font-weight:500;color:rgba(255,255,255,0.8);margin-bottom:0.25rem;">RON</span>
                    <span style="font-size:0.875rem;color:rgba(255,255,255,0.6);margin-bottom:0.3rem;">/ lună</span>
                </div>
                <p style="font-size:0.8125rem;color:rgba(255,255,255,0.65);margin:0.5rem 0 0;">Prima lună gratuită, anulezi oricând.</p>
            </div>

            {{-- Features --}}
            <div style="padding:1.25rem 1.75rem;">
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0.625rem;">
                    @foreach([
                        'Facturi nelimitate cu PDF și email',
                        'Gestionare clienți și produse',
                        'Tracker cheltuieli cu upload bonuri',
                        'Rapoarte financiare și TVA',
                        'Export CSV / Excel',
                        'Notificări automate restanțe',
                    ] as $feature)
                        <li style="display:flex;align-items:center;gap:0.625rem;font-size:0.875rem;color:#374151;">
                            <svg width="15" height="15" fill="none" stroke="#0d9488" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Action --}}
            <div style="padding:1.25rem 1.75rem 1.75rem;display:flex;flex-direction:column;align-items:center;gap:0.625rem;">
                @if($user->subscription_status === 'active')
                    <a href="{{ route('subscription.portal') }}"
                       style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1.5rem;font-size:0.875rem;font-weight:500;color:#374151;border:1px solid #d1d5db;border-radius:0.625rem;background:#fff;text-decoration:none;transition:background .15s;"
                       onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Gestionează abonamentul
                    </a>
                    <p style="font-size:0.75rem;color:#9ca3af;margin:0;">Modifici sau anulezi din portalul Stripe.</p>
                @else
                    <form method="POST" action="{{ route('subscription.checkout') }}" style="width:100%;display:flex;justify-content:center;">
                        @csrf
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.7rem 2rem;font-size:0.9375rem;font-weight:600;color:#fff;background:#0d9488;border:none;border-radius:0.625rem;cursor:pointer;transition:background .15s;"
                                onmouseover="this.style.background='#0f766e'" onmouseout="this.style.background='#0d9488'">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Abonează-te
                        </button>
                    </form>
                    <p style="font-size:0.75rem;color:#9ca3af;margin:0;">Plată securizată prin Stripe. Anulezi oricând.</p>
                @endif
            </div>
        </div>

        <p style="margin-top:1.25rem;text-align:center;font-size:0.8125rem;color:#9ca3af;">
            <a href="{{ route('dashboard') }}" style="color:#6b7280;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#6b7280'">← Înapoi la dashboard</a>
        </p>

    </div>
</x-cashly-layout>
