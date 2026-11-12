@extends('layouts.admin')

@section('title', $quiz->title)

@section('content')
    <a href="{{ route('admin.quizzes.index') }}" class="text-blue-600 hover:underline">
        &larr; Wróć do listy quizów
    </a>

    {{-- ===== Informacje o quizie ===== --}}
    <div class="bg-white p-6 rounded shadow mt-4 mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>
                @if ($quiz->description)
                    <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                @endif
            </div>
            <a href="{{ route('admin.quizzes.edit', $quiz) }}"
               class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 whitespace-nowrap">
                Edytuj quiz
            </a>
            <a href="{{ route('admin.quizzes.results', $quiz) }}"
                class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 whitespace-nowrap">
                Wyniki
            </a>
        </div>

        <dl class="flex flex-wrap gap-8 mt-4 text-sm">
            <div>
                <dt class="text-gray-500">Kategoria</dt>
                <dd class="font-medium">{{ $quiz->category->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Liczba pytań</dt>
                <dd class="font-medium">{{ $quiz->questions->count() }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Limit czasu</dt>
                <dd class="font-medium">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Brak' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd @class([
                    'font-medium',
                    'text-green-700' => $quiz->is_published,
                    'text-gray-600' => ! $quiz->is_published,
                ])>
                    {{ $quiz->is_published ? 'Opublikowany' : 'Szkic' }}
                </dd>
            </div>
        </dl>

        @if ($quiz->is_published && $quiz->questions->isEmpty())
            <p class="mt-4 bg-yellow-100 text-yellow-800 px-4 py-2 rounded">
                Uwaga: quiz jest opublikowany, ale nie ma pytań.
            </p>
        @endif
    </div>

    {{-- ===== Pytania i odpowiedzi ===== --}}
    <div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold">Pytania</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.quizzes.questions.create', ['quiz' => $quiz, 'type' => 'single_choice']) }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Pytanie A–D
            </a>
            <a href="{{ route('admin.quizzes.questions.create', ['quiz' => $quiz, 'type' => 'text']) }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Pytanie z wpisaniem
            </a>
        </div>
    </div>

    @forelse ($quiz->questions as $question)
        <div class="bg-white p-5 rounded shadow mb-4">
           <div class="flex items-start justify-between gap-4 mb-3">
                <h3 class="font-semibold">
                    {{ $loop->iteration }}. {{ $question->content }}
                </h3>
                <p class="text-xs text-gray-500 mb-2">{{ $question->type->label() }}</p>
                <div class="whitespace-nowrap text-sm">
                    <a href="{{ route('admin.questions.edit', $question) }}"
                    class="text-blue-600 hover:underline">Edytuj</a>
                    <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="inline"
                        onsubmit="return confirm('Usunąć to pytanie razem z odpowiedziami?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-3">Usuń</button>
                    </form>
                </div>
            </div>

            <ul class="space-y-2">
                @foreach ($question->answers as $answer)
                    <li @class([
                        'px-3 py-2 rounded border',
                        'bg-green-50 border-green-400 text-green-800 font-medium' => $answer->is_correct,
                        'border-gray-200' => ! $answer->is_correct,
                    ])>
                        {{ chr(64 + $loop->iteration) }}) {{ $answer->content }}
                        @if ($answer->is_correct)
                            <span class="ml-2">✓ poprawna</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <div class="bg-white p-6 rounded shadow text-center text-gray-500">
            Ten quiz nie ma jeszcze pytań.
        </div>
    @endforelse
@endsection