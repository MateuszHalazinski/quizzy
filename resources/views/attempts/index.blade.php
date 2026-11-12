@extends('layouts.app')

@section('title', 'Moje wyniki')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Moje wyniki</h1>

    {{-- ===== W trakcie ===== --}}
    @if ($inProgress->isNotEmpty())
        <div class="bg-yellow-50 border border-yellow-300 p-4 rounded mb-6">
            <h2 class="font-semibold mb-2">Rozpoczęte, niedokończone</h2>
            <ul class="space-y-1">
                @foreach ($inProgress as $attempt)
                    <li>
                        {{ $attempt->quiz->title }} –
                        <a href="{{ route('attempts.show', $attempt) }}" class="text-blue-700 hover:underline">Kontynuuj</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== Zakończone ===== --}}
    <p class="text-gray-600 mb-3">Zakończone quizy: {{ $attempts->total() }}</p>

    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left border-b">
                <th class="p-3">Quiz</th>
                <th class="p-3">Kategoria</th>
                <th class="p-3">Wynik</th>
                <th class="p-3">Data</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attempts as $attempt)
                <tr class="border-b">
                    <td class="p-3 font-medium">{{ $attempt->quiz->title }}</td>
                    <td class="p-3">{{ $attempt->quiz->category->name }}</td>
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
                        <a href="{{ route('attempts.result', $attempt) }}" class="text-blue-700 hover:underline">Szczegóły</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
                        Nie masz jeszcze żadnych wyników.
                        <a href="{{ route('quizzes.index') }}" class="text-blue-700 hover:underline">Rozwiąż pierwszy quiz!</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $attempts->links() }}
    </div>
@endsection