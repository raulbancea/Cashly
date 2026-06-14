<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly - Acces interzis</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans bg-gray-50">

<div class="flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md text-center">

        <div class="mb-6">
            <span style="font-size:7rem;font-weight:800;color:#e2e8f0;line-height:1;display:block;">403</span>
            <div class="w-16 h-1 mx-auto -mt-3 bg-teal-500 rounded-full"></div>
        </div>

        <h1 class="mb-2 text-2xl font-bold text-gray-900">Acces interzis</h1>
        <p class="mb-8 text-sm leading-relaxed text-gray-500">
            Nu ai permisiunea să accesezi această resursă. Dacă crezi că e o eroare, contactează administratorul.
        </p>

        <div class="flex items-center justify-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-colors">
                    Mergi la Dashboard
                </a>
                <a href="javascript:history.back()"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Înapoi
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-colors">
                    Autentifică-te
                </a>
            @endauth
        </div>
    </div>
</div>

</body>
</html>
