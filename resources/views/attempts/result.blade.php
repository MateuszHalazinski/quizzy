@extends('layouts.app')

@section('title', 'Wynik – ' . $attempt->quiz->title)

@section('content')
    {{-- ===== Podsumowanie ===== --}}
    <div class="bg-white p-6 rounded shadow mb-6">
        <p class="text-sm text-gray-500">Wynik quizu</p>
        <h1 class="text-2xl font-bold">{{ $attempt->quiz->title }}</h1>

        <p class="text-4xl font-bold mt-4">
            {{ $attempt->score }} / {{ $attempt->total }}
            <span class="text-2xl text-gray-500">({{ $percent }}%)</span>
        </p>

        <div class="w-full bg-gray-200 rounded-full h-3 mt-3">
            <div @class([
                    'h-3 rounded-full',
                    'bg-green-500' => $percent >= 80,
                    'bg-yellow-500' => $percent >= 50 && $percent < 80,
                    'bg-red-500' => $percent < 50,
                 ])
                 style="width: {{ $percent }}%"></div>
        </div>

        <p class="text-sm text-gray-500 mt-3">
            Czas rozwiązywania: {{ $minutes }} min {{ $seconds }} s
        </p>

        <div class="flex gap-3 mt-6">
            <form method="POST" action="{{ route('attempts.store', $attempt->quiz) }}">
                @csrf
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                    Spróbuj ponownie
                </button>
            </form>
            <a href="{{ route('quizzes.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Wszystkie quizy</a>
            <a href="{{ route('attempts.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Moje wyniki</a>
        </div>
    </div>

    {{-- ===== Pytania ===== --}}
    <h2 class="text-xl font-bold mb-4">Twoje odpowiedzi</h2>

    @foreach ($attempt->answers as $row)
        <div class="bg-white p-5 rounded shadow mb-4">
            <div class="flex items-start justify-between gap-4 mb-3">
                <h3 class="font-semibold">{{ $loop->iteration }}. {{ $row->question->content }}</h3>

                @if ($row->answer_id === null && $row->text_answer === null)
                    <span class="text-sm bg-gray-200 text-gray-700 px-2 py-1 rounded whitespace-nowrap">Brak odpowiedzi</span>
                @elseif ($row->isCorrect())
                    <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded whitespace-nowrap">Dobrze</span>
                @else
                    <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded whitespace-nowrap">Źle</span>
                @endif
            </div>

            {{-- Część zależna od typu pytania --}}
            @include('attempts.result-types.' . $row->question->type->value)
        </div>
    @endforeach
@endsection