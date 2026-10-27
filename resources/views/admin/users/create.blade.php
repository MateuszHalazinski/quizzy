@extends('layouts.admin')

@section('title', 'Dodaj użytkownika')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dodaj użytkownika</h1>

    <form method="POST" action="{{ route('admin.users.store') }}"
          class="bg-white p-6 rounded shadow max-w-lg space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-medium mb-1">Imię i nazwisko</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="username" class="block font-medium mb-1">Login</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}"
                   class="w-full border rounded px-3 py-2">
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

        <div>
            <label for="password_confirmation" class="block font-medium mb-1">Powtórz hasło</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin'))>
                Administrator
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Zapisz
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 hover:underline">
                Anuluj
            </a>
        </div>
    </form>
@endsection