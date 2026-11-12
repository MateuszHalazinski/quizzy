@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
    <div class="flex items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>

        @if ($secondsLeft !== null)
            <div id="timer" data-seconds="{{ $secondsLeft }}"
                 class="sticky top-2 bg-yellow-100 text-yellow-900 font-semibold px-4 py-2 rounded shadow">
                Pozostało: <span id="timer-value"></span>
            </div>
        @endif
    </div>

    <form id="quiz-form" method="POST" action="{{ route('attempts.finish', $attempt) }}"
          onsubmit="return confirm('Zakończyć quiz i sprawdzić odpowiedzi?')">
        @csrf

        @foreach ($questions as $question)
            <div class="bg-white p-5 rounded shadow mb-4">
                <h2 class="font-semibold mb-3">{{ $loop->iteration }}. {{ $question->content }}</h2>

                {{-- Część zależna od typu pytania --}}
                @include('attempts.types.' . $question->type->value)
            </div>
        @endforeach

        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">
            Zakończ quiz
        </button>
    </form>

    @if ($secondsLeft !== null)
        <script>
            const timer = document.getElementById('timer');
            const timerValue = document.getElementById('timer-value');
            const quizForm = document.getElementById('quiz-form');
            let seconds = Number(timer.dataset.seconds);

            function tick() {
                const minutes = Math.floor(seconds / 60);
                const rest = seconds % 60;
                timerValue.textContent = minutes + ':' + String(rest).padStart(2, '0');

                if (seconds <= 0) {
                    quizForm.submit();
                    return;
                }

                seconds--;
                setTimeout(tick, 1000);
            }

            tick();
        </script>
    @endif
@endsection