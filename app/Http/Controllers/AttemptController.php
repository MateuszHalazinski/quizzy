<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\QuestionType;

class AttemptController extends Controller
{

    // GET /my-results – historia podejść użytkownika
    public function index(Request $request)
    {
        $user = $request->user();

        // Podejścia w trakcie (do wznowienia)
        $inProgress = $user->attempts()
            ->unfinished()
            ->with('quiz')
            ->latest()
            ->get();

        // Zakończone podejścia, po 10 na stronę
        $attempts = $user->attempts()
            ->finished()
            ->with('quiz.category')
            ->latest('finished_at')
            ->paginate(10);

        return view('attempts.index', compact('inProgress', 'attempts'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->is_published && $quiz->questions()->exists(), 404);

        $user = $request->user();

        $attempt = $user->attempts()
            ->where('quiz_id', $quiz->id)
            ->unfinished()
            ->first();
        if (! $attempt) {
            $attempt = $user->attempts()->create([
                'quiz_id' => $quiz->id,
            ]);
        }

        return redirect()->route('attempts.show', $attempt);
    }


    public function show(Request $request, Attempt $attempt)
    {
        abort_unless($request->user()->is($attempt->user), 403);

        if ($attempt->finished_at) {
        return redirect()->route('attempts.result', $attempt);}

        $quiz = $attempt->quiz;

        $questions = $quiz->questions()->with('answers')->inRandomOrder()->get();

        foreach ($questions as $question) {
            $question->setRelation('answers', $question->answers->shuffle());
        }

        $secondsLeft = null;

        if ($quiz->time_limit) {
            $deadline = $attempt->started_at->copy()->addMinutes($quiz->time_limit);
            $secondsLeft = max(0, $deadline->timestamp - now()->timestamp);
        }

        return view('attempts.show', compact('attempt', 'quiz', 'questions', 'secondsLeft'));
    }

    // POST /attempts/{attempt}/finish – sprawdzenie odpowiedzi i zapis wyniku
    // POST /attempts/{attempt}/finish – sprawdzenie odpowiedzi i zapis wyniku
    public function finish(Request $request, Attempt $attempt)
    {
        // 1. Tylko właściciel
        abort_unless($request->user()->is($attempt->user), 403);

        // 2. Już zakończone – nie liczymy drugi raz
        if ($attempt->finished_at) {
            return redirect()->route('attempts.result', $attempt);
        }

        $request->validate([
            'answers' => ['nullable', 'array'],
        ]);

        $quiz = $attempt->quiz;

        // 3. Czy czas minął? (10 sekund zapasu)
        $timeIsUp = false;

        if ($quiz->time_limit) {
            $deadline = $attempt->started_at->copy()
                ->addMinutes($quiz->time_limit)
                ->addSeconds(10);

            $timeIsUp = now()->greaterThan($deadline);
        }

        $given = $timeIsUp ? [] : $request->input('answers', []);

        // 4. Sprawdź każde pytanie quizu – zależnie od typu
        $questions = $quiz->questions()->with('answers')->get();
        $rows = [];
        $score = 0;

        foreach ($questions as $question) {
            $value = $given[$question->id] ?? null;

            if ($question->type === QuestionType::Text) {
                // Wpisanie: porównaj z akceptowanymi (bez wielkości liter i spacji na końcach)
                $text = is_string($value) ? trim($value) : '';

                $answer = $text === ''
                    ? null
                    : $question->answers->first(
                        fn ($accepted) => mb_strtolower(trim($accepted->content)) === mb_strtolower($text)
                    );

                $rows[] = [
                    'question_id' => $question->id,
                    'answer_id' => $answer?->id,
                    'text_answer' => $text !== '' ? $text : null,
                ];
            } else {
                // A–D: szukaj zaznaczonej odpowiedzi tylko wśród odpowiedzi tego pytania
                $answer = $question->answers->firstWhere('id', (int) $value);

                $rows[] = [
                    'question_id' => $question->id,
                    'answer_id' => $answer?->id,
                    'text_answer' => null,
                ];
            }

            if ($answer?->is_correct) {
                $score++;
            }
        }

        // 5. Zapis – wszystko albo nic
        DB::transaction(function () use ($attempt, $rows, $score, $questions) {
            $attempt->answers()->createMany($rows);

            $attempt->update([
                'finished_at' => now(),
                'score' => $score,
                'total' => $questions->count(),
            ]);
        });

        // 6. Wynik
        if ($timeIsUp) {
            return redirect()
                ->route('attempts.result', $attempt)
                ->with('error', 'Czas minął – odpowiedzi wysłane po czasie nie zostały policzone.');
        }

        return redirect()
            ->route('attempts.result', $attempt)
            ->with('success', 'Quiz zakończony!');
    }

   // GET /attempts/{attempt}/result – wynik podejścia
    public function result(Request $request, Attempt $attempt)
    {
        abort_unless($request->user()->is($attempt->user) || $request->user()->is_admin, 403);

        // Niedokończone podejście nie ma jeszcze wyniku
        if (! $attempt->finished_at) {
            return redirect()->route('attempts.show', $attempt);
        }

        $attempt->load([
            'quiz',
            'answers.question.answers',   // pytanie i WSZYSTKIE jego odpowiedzi (żeby pokazać poprawną)
            'answers.answer',             // odpowiedź zaznaczona przez użytkownika
        ]);
        
        $percent = $attempt->percent();

        // Czas rozwiązywania
        $duration = $attempt->finished_at->timestamp - $attempt->started_at->timestamp;
        $minutes = intdiv($duration, 60);
        $seconds = $duration % 60;

        return view('attempts.result', compact('attempt', 'percent', 'minutes', 'seconds'));
    }
}