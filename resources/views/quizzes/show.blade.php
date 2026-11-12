@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
    <a href="{{ route('quizzes.index') }}" class="text-blue-700 hover:underline">&larr; Wszystkie quizy</a>

    <div class="bg-white p-6 rounded shadow mt-4 max-w-2xl">
        <p class="text-sm text-blue-700 mb-1">{{ $quiz->category->name }}</p>
        <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>

        @if ($quiz->description)
            <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
        @endif

        <ul class="mt-4 space-y-1 text-gray-700">
            <li>Liczba pytań: <strong>{{ $quiz->questions_count }}</strong></li>
            <li>Limit czasu: <strong>{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'brak' }}</strong></li>
        </ul>

        @if ($quiz->time_limit)
            <p class="mt-4 bg-yellow-100 text-yellow-800 px-4 py-2 rounded">
                Czas zacznie płynąć po kliknięciu „Rozpocznij”. Po jego upływie odpowiedzi nie zostaną przyjęte.
            </p>
        @endif

        <form method="POST" action="{{ route('attempts.store', $quiz) }}" class="mt-6">
        @csrf
        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">
            {{ $hasUnfinished ? 'Kontynuuj' : 'Rozpocznij' }}
        </button>
    </form>
    </div>
@endsection