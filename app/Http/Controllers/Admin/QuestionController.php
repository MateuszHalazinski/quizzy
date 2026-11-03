<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Quiz $quiz) 
    {
        $question = new Question; 

        return view('admin.questions.create', compact('quiz', 'question')); 
    }

    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'answers' => ['array', 'max:4'],
            'answers.*' => ['nullable', 'string', 'max:255'],
            'correct' => ['required', 'integer', 'between:0,3'],
        ], [
            'correct.required' => 'Zaznacz poprawną odpowiedź.', 
        ]);

        $content = $request->input('content'); 
        $answers = $request->input('answers', []); 
        $correct = (int) $request->input('correct');  

        $filledCount = 0; 

        foreach ($answers as $answer) { 
            if ($answer !== null) {
                $filledCount++;
            }
        }

        if ($filledCount < 2) {
            return back()
                ->withErrors(['answers' => 'Podaj co najmniej 2 odpowiedzi.'])
                ->withInput();
        }

        
        if (! isset($answers[$correct])) { 
            return back()
                ->withErrors(['correct' => 'Zaznaczona poprawna odpowiedź jest pusta.'])
                ->withInput();
        }

       
        $question = $quiz->questions()->create([ 
            'content' => $content,
        ]);

        
        foreach ($answers as $index => $answerContent) {
            if ($answerContent === null) {
                continue;   
            }

            $question->answers()->create([
                'content' => $answerContent,
                'is_correct' => $index === $correct,
            ]);
        }

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pytanie zostało dodane.');
    }

    public function edit(Question $question) 
    {
        $quiz = $question->quiz; 

        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'answers' => ['array', 'max:4'],
            'answers.*' => ['nullable', 'string', 'max:255'],
            'correct' => ['required', 'integer', 'between:0,3'],
        ], [
            'correct.required' => 'Zaznacz poprawną odpowiedź.',
        ]);

        $content = $request->input('content');
        $answers = $request->input('answers', []);
        $correct = (int) $request->input('correct');

        $filledCount = 0;

        foreach ($answers as $answer) {
            if ($answer !== null) {
                $filledCount++;
            }
        }

        if ($filledCount < 2) {
            return back()
                ->withErrors(['answers' => 'Podaj co najmniej 2 odpowiedzi.'])
                ->withInput();
        }

        if (! isset($answers[$correct])) {
            return back()
                ->withErrors(['correct' => 'Zaznaczona poprawna odpowiedź jest pusta.'])
                ->withInput();
        }
       
        $question->update([ 
            'content' => $content,
        ]);

        $question->answers()->delete();

        foreach ($answers as $index => $answerContent) { 
            if ($answerContent === null) {
                continue;
            }

            $question->answers()->create([
                'content' => $answerContent,
                'is_correct' => $index === $correct,
            ]);
        }

        return redirect()
            ->route('admin.quizzes.show', $question->quiz)
            ->with('success', 'Pytanie zostało zapisane.');
    }

    public function destroy(Question $question)
    {
        // zapamiętaj quiz zanim usuniesz pytanie, potrzebny do przekierowania
        $quiz = $question->quiz;

        $question->delete();

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pytanie zostało usunięte.');
    }
}