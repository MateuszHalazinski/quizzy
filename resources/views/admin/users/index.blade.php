@extends('layouts.admin')

@section('title', 'Użytkownicy')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Użytkownicy</h1>
        <a href="{{ route('admin.users.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Dodaj użytkownika
        </a>
    </div>

    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left border-b">
                <th class="p-3">Imię i nazwisko</th>
                <th class="p-3">Login</th>
                <th class="p-3">Rola</th>
                <th class="p-3">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b">
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->username }}</td>
                    <td class="p-3">
                        @if ($user->is_admin)
                            <span class="text-red-600 font-semibold">Admin</span>
                        @else
                            Użytkownik
                        @endif
                    </td>
                    <td class="p-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-blue-600 hover:underline">Edytuj</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                            onsubmit="return confirm('Na pewno usunąć tego użytkownika?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-3">Usuń</button>
                        </form>
                    </td>                               
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection