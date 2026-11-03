@extends('layouts.admin')

@section('title', 'Quizy')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Quizy</h1>
        <a href="{{ route('admin.quizzes.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Dodaj quiz
        </a>
    </div>

    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left border-b">
                <th class="p-3">Tytuł</th>
                <th class="p-3">Kategoria</th>
                <th class="p-3">Pytania</th>
                <th class="p-3">Limit czasu</th>
                <th class="p-3">Status</th>
                <th class="p-3">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quizzes as $quiz)
                <tr class="border-b">
                    <td class="p-3 font-medium">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="hover:underline">{{ $quiz->title }}</a></td>                   
                    <td class="p-3">{{ $quiz->category->name }}</td>
                    <td class="p-3">{{ $quiz->questions_count }}</td>
                    <td class="p-3">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Brak' }}</td>
                    <td class="p-3">
                        <span @class([
                            'px-2 py-1 rounded text-sm',
                            'bg-green-100 text-green-800' => $quiz->is_published,
                            'bg-gray-200 text-gray-700' => ! $quiz->is_published,
                        ])>
                            {{ $quiz->is_published ? 'Opublikowany' : 'Szkic' }}
                        </span>
                    </td>
                    <td class="p-3 whitespace-nowrap">
                        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-blue-600 hover:underline mr-3">Pytania</a>
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                           class="text-blue-600 hover:underline">Edytuj</a>

                        <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="inline"
                              onsubmit="return confirm('Usunąć quiz? Zostaną usunięte również wszystkie jego pytania!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-3">Usuń</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        Brak quizów. Dodaj pierwszy!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection