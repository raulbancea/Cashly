<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonament activat — Cashly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center max-w-sm mx-auto p-8">
        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Abonament activat!</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-6">
            Mulțumim! Abonamentul tău Cashly Pro a fost activat cu succes.<br>
            Ai acces complet la toate funcționalitățile.
        </p>
        <a href="{{ route('dashboard') }}"
           class="inline-block px-6 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
            Mergi la Dashboard
        </a>
    </div>
</body>
</html>
