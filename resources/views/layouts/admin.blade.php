<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel admina') – Quiz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-gray-800 text-white">
    <div class="max-w-5xl mx-auto px-4 py-3 flex gap-6">
        <span class="font-bold">Panel admina</span>
        <a href="{{ route('admin.users.index') }}" class="hover:underline">Użytkownicy</a>

        @auth
            <div class="ml-auto flex items-center gap-4">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:underline">Wyloguj</button>
                </form>
            </div>
        @endauth
    </div>
</nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
         @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
         @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
         @endif
        @yield('content')
    </main>
</body>
</html>