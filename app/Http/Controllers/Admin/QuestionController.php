<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuestionType;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    // GET /admin/quizzes/{quiz}/questions/create?type=text – formularz nowego pytania
    public function create(Request $request, Quiz $quiz)
    {
        $question = new Question;
        $question->type = $request->enum('type', QuestionType::class) ?? QuestionType::SingleChoice;

        return view('admin.questions.create', compact('quiz', 'question'));
    }

    // POST /admin/quizzes/{quiz}/questions – zapis nowego pytania
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'type' => ['required', Rule::enum(QuestionType::class)],
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $type = $request->enum('type', QuestionType::class);

        // Sprawdzenie odpowiedzi – inne reguły dla każdego typu
        $slots = $this->answerSlots($request, $type);

        DB::transaction(function () use ($quiz, $request, $type, $slots) {
            $question = $quiz->questions()->create([
                'type' => $type,
                'content' => $request->input('content'),
            ]);

            $this->syncAnswers($question, $slots);
        });

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pytanie zostało dodane.');
    }

    // GET /admin/questions/{question}/edit – formularz edycji
    public function edit(Question $question)
    {
        $quiz = $question->quiz;

        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    // PUT /admin/questions/{question} – zapis zmian (typu pytania nie zmieniamy)
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $slots = $this->answerSlots($request, $question->type);

        DB::transaction(function () use ($question, $request, $slots) {
            $question->update([
                'content' => $request->input('content'),
            ]);

            $this->syncAnswers($question, $slots);
        });

        return redirect()
            ->route('admin.quizzes.show', $question->quiz)
            ->with('success', 'Pytanie zostało zapisane.');
    }

    // DELETE /admin/questions/{question} – usunięcie pytania
    public function destroy(Question $question)
    {
        $quiz = $question->quiz;

        $question->delete();

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pytanie zostało usunięte.');
    }

    // ===== Metody pomocnicze =====

    // Wybiera sposób sprawdzania odpowiedzi według typu pytania
    private function answerSlots(Request $request, QuestionType $type): array
    {
        return match ($type) {
            QuestionType::SingleChoice => $this->choiceSlots($request),
            QuestionType::Text => $this->textSlots($request),
        };
    }

    // Typ A–D: 2–4 odpowiedzi, dokładnie jedna poprawna
    private function choiceSlots(Request $request): array
    {
        $request->validate([
            'answers' => ['array', 'max:4'],
            'answers.*' => ['nullable', 'string', 'max:255'],
            'correct' => ['required', 'integer', 'between:0,3'],
        ], [
            'correct.required' => 'Zaznacz poprawną odpowiedź.',
        ]);

        $answers = $request->input('answers', []);
        $correct = (int) $request->input('correct');

        $filledCount = 0;

        foreach ($answers as $answer) {
            if ($answer !== null) {
                $filledCount++;
            }
        }

        if ($filledCount < 2) {
            throw ValidationException::withMessages(['answers' => 'Podaj co najmniej 2 odpowiedzi.']);
        }

        if (! isset($answers[$correct])) {
            throw ValidationException::withMessages(['correct' => 'Zaznaczona poprawna odpowiedź jest pusta.']);
        }

        // 4 pozycje A–D: dane odpowiedzi albo null (puste pole)
        $slots = [];

        for ($i = 0; $i < 4; $i++) {
            $slots[$i] = isset($answers[$i])
                ? ['content' => $answers[$i], 'is_correct' => $i === $correct]
                : null;
        }

        return $slots;
    }

    // Typ „wpisanie”: 1–4 akceptowane odpowiedzi, wszystkie poprawne
    private function textSlots(Request $request): array
    {
        $request->validate([
            'answers' => ['array', 'max:4'],
            'answers.*' => ['nullable', 'string', 'max:255'],
        ]);

        $answers = $request->input('answers', []);
        $slots = [];
        $filledCount = 0;

        for ($i = 0; $i < 4; $i++) {
            if (isset($answers[$i])) {
                $slots[$i] = ['content' => $answers[$i], 'is_correct' => true];
                $filledCount++;
            } else {
                $slots[$i] = null;
            }
        }

        if ($filledCount < 1) {
            throw ValidationException::withMessages(['answers' => 'Podaj co najmniej jedną akceptowaną odpowiedź.']);
        }

        return $slots;
    }

    // Zapis odpowiedzi pozycja po pozycji: aktualizuj / usuń / utwórz (logika z kroku 7)
    private function syncAnswers(Question $question, array $slots): void
    {
        $existing = $question->answers()->orderBy('id')->get();

        for ($i = 0; $i < 4; $i++) {
            $slot = $slots[$i];
            $answer = $existing->get($i);

            if ($answer && $slot) {
                $answer->update($slot);
            } elseif ($answer && ! $slot) {
                $answer->delete();
            } elseif (! $answer && $slot) {
                $question->answers()->create($slot);
            }
        }
    }
}