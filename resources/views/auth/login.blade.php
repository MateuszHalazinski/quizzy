<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie – Quiz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex items-center justify-center">
    <form method="POST" action="{{ route('login.store') }}"
          class="bg-white p-8 rounded shadow w-full max-w-sm space-y-4">
        @csrf

        <h1 class="text-2xl font-bold text-center">Logowanie</h1>

        <div>
            <label for="username" class="block font-medium mb-1">Login</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}"
                   class="w-full border rounded px-3 py-2" autofocus>
            @error('username')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block font-medium mb-1">Hasło</label>
            <input type="password" id="password" name="password"
                   class="w-full border rounded px-3 py-2">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="remember" value="1">
            Zapamiętaj mnie
        </label>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Zaloguj
        </button>
    </form>
</body>
</html>