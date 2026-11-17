<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuestionType;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TextQuestionController extends Controller
{
    public function create(Quiz $quiz)
    {
        $question = new Question; //tworzymy pusty obiekt ze względu na widok formularza

        return view('admin.text-questions.create', compact('quiz', 'question')); //przekazujemy widokowi dane o quiz i question. quizz jest potrzbne do adresu
    }

    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'answers' => ['array', 'max:4'],
            'answers.*' => ['nullable', 'string', 'max:255'],
        ]); 

        $content = $request->input('content');
        $answers = $request->input('answers', []); //zabezpieczenie pozwala zostawić null

        $filledCount = 0;

        foreach ($answers as $answer) {
            if ($answer !== null) {
                $filledCount++;
            }
        }

        if ($filledCount < 1) {
            return back()
                ->withErrors(['answers' => 'Podaj co najmniej jedną akceptowaną odpowiedź.'])
                ->withInput();
        }

        DB::transaction(function () use ($quiz, $content, $answers) { 
            $question = $quiz->questions()->create([
                'type' => QuestionType::Text,
                'content' => $content,
            ]);

            foreach ($answers as $answerContent) {
                if ($answerContent === null) { 
                    continue; 
                }

                $question->answers()->create([
                    'content' => $answerContent,
                    'is_correct' => true,  
                ]);
            }
        });

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pytanie zostało dodane.');
    }

    public function edit(Question $question) 
    {
        $quiz = $question->quiz; 

        return view('admin.text-questions.edit', compact('quiz', 'question')); 
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'], 
            'answers' => ['array', 'max:4'],
            'answers.*' => ['nullable', 'string', 'max:255'],
        ]); 

        $content = $request->input('content'); 
        $answers = $request->input('answers', []); // jeśli pole answers w ogóle nie przyszło, daj pustą tablicę

        $filledCount = 0;

        foreach ($answers as $answer) {
            if ($answer !== null) {
                $filledCount++;
            }
        }

        if ($filledCount < 1) {
            return back()
                ->withErrors(['answers' => 'Podaj co najmniej jedną akceptowaną odpowiedź.'])
                ->withInput();
        }

        DB::transaction(function () use ($question, $content, $answers) { 
            $question->update([
                'content' => $content,
            ]);

            $existing = $question->answers()->orderBy('id')->get(); 

            for ($i = 0; $i < 4; $i++) {
                $answerContent = $answers[$i] ?? null; 
                $answer = $existing->get($i); //dla każdej pozycji bierzemy co zostało wpisane i co było w bazie względem pozycji $i

                if ($answer && $answerContent !== null) {
                    $answer->update(['content' => $answerContent, 'is_correct' => true]);
                } elseif ($answer && $answerContent === null) {
                    $answer->delete();
                } elseif (! $answer && $answerContent !== null) {
                    $question->answers()->create(['content' => $answerContent, 'is_correct' => true]);
                }
            }
        });

        return redirect()
            ->route('admin.quizzes.show', $question->quiz)
            ->with('success', 'Pytanie zostało zapisane.');
    }

    public function destroy(Question $question) 
    {
        $quiz = $question->quiz;

        $question->delete(); 

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pytanie zostało usunięte.');
    }
}