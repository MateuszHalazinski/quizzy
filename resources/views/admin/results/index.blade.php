@extends('layouts.admin')

@section('title', 'Wyniki – ' . $quiz->title)

@section('content')
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-blue-600 hover:underline">
        &larr; Wróć do quizu
    </a>

    <h1 class="text-2xl font-bold mt-4 mb-6">Wyniki: {{ $quiz->title }}</h1>

    {{-- Podsumowanie --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Podejścia</p>
            <p class="text-2xl font-bold">{{ $attempts->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Średni wynik</p>
            <p class="text-2xl font-bold">{{ $average !== null ? $average . '%' : '—' }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Najlepszy wynik</p>
            <p class="text-2xl font-bold">{{ $best !== null ? $best . '%' : '—' }}</p>
        </div>
    </div>

    {{-- Lista podejść --}}
    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left border-b">
                <th class="p-3">Użytkownik</th>
                <th class="p-3">Wynik</th>
                <th class="p-3">Data</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attempts as $attempt)
                <tr class="border-b">
                    <td class="p-3">
                        {{ $attempt->user->name }}
                        <span class="text-gray-500 text-sm">({{ $attempt->user->username }})</span>
                    </td>
                    <td class="p-3">
                        <span @class([
                            'font-semibold',
                            'text-green-700' => $attempt->percent() >= 80,
                            'text-yellow-700' => $attempt->percent() >= 50 && $attempt->percent() < 80,
                            'text-red-700' => $attempt->percent() < 50,
                        ])>
                            {{ $attempt->score }} / {{ $attempt->total }} ({{ $attempt->percent() }}%)
                        </span>
                    </td>
                    <td class="p-3 text-gray-600">
                        {{ $attempt->finished_at->timezone('Europe/Warsaw')->format('d.m.Y H:i') }}
                    </td>
                    <td class="p-3">
                        <a href="{{ route('attempts.result', $attempt) }}" class="text-blue-600 hover:underline">Szczegóły</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">Nikt jeszcze nie rozwiązał tego quizu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection