@extends('layouts.admin')

@section('title', 'Kategorie')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Kategorie</h1>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Dodaj kategorię
        </a>
    </div>

    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left border-b">
                <th class="p-3">Nazwa</th>
                <th class="p-3">Liczba quizów</th>
                <th class="p-3">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr class="border-b">
                    <td class="p-3">{{ $category->name }}</td>
                    <td class="p-3">{{ $category->quizzes_count }}</td>
                    <td class="p-3">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="text-blue-600 hover:underline">Edytuj</a>

                        @if ($category->quizzes_count === 0)
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  class="inline" onsubmit="return confirm('Na pewno usunąć tę kategorię?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline ml-3">Usuń</button>
                            </form>
                        @else
                            <span class="text-gray-400 ml-3" title="Najpierw usuń lub przenieś quizy">Usuń</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-6 text-center text-gray-500">
                        Brak kategorii. Dodaj pierwszą!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection