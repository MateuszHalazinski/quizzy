@extends('layouts.app')

@section('title', 'Quizy')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Quizy</h1>

    {{-- Filtr kategorii --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('quizzes.index') }}"
           @class([
               'px-3 py-1 rounded-full border',
               'bg-blue-700 text-white border-blue-700' => ! $categoryId,
               'bg-white hover:bg-gray-50' => $categoryId,
           ])>
            Wszystkie
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('quizzes.index', ['category' => $category->id]) }}"
               @class([
                   'px-3 py-1 rounded-full border',
                   'bg-blue-700 text-white border-blue-700' => $categoryId === $category->id,
                   'bg-white hover:bg-gray-50' => $categoryId !== $category->id,
               ])>
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Lista quizów --}}
    <div class="grid gap-4 sm:grid-cols-2">
        @forelse ($quizzes as $quiz)
            <a href="{{ route('quizzes.show', $quiz) }}"
               class="block bg-white p-5 rounded shadow hover:shadow-md transition">
                <p class="text-sm text-blue-700 mb-1">{{ $quiz->category->name }}</p>
                <h2 class="text-lg font-semibold">{{ $quiz->title }}</h2>
                @if ($quiz->description)
                    <p class="text-gray-600 mt-1">{{ $quiz->description }}</p>
                @endif
                <p class="text-sm text-gray-500 mt-3">
                    {{ $quiz->questions_count }} pytań ·
                    {{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'bez limitu czasu' }}
                </p>
            </a>
        @empty
            <p class="text-gray-500">Brak quizów w tej kategorii.</p>
        @endforelse
    </div>
@endsection