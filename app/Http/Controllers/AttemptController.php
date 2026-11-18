<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\QuestionType;

class AttemptController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $inProgress = $user->attempts()
            ->unfinished()
            ->with('quiz')
            ->latest()
            ->get();

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

    public function finish(Request $request, Attempt $attempt)
    {
        abort_unless($request->user()->is($attempt->user), 403);

        if ($attempt->finished_at) {
            return redirect()->route('attempts.result', $attempt);
        }

        $request->validate([
            'answers' => ['nullable', 'array'],
        ]);

        $quiz = $attempt->quiz;

        $timeIsUp = false;

        if ($quiz->time_limit) {
            $deadline = $attempt->started_at->copy()
                ->addMinutes($quiz->time_limit)
                ->addSeconds(10);

            $timeIsUp = now()->greaterThan($deadline);
        }
        //Odczytujemy z wysłanego formularza wszystkie zaznaczone odpowiedzi razem z id pytania 
        if ($timeIsUp === true) {
            $given = [];                                  
        } 
        else 
        {
            $given = $request->input('answers', []);     
        }

        $questions = $quiz->questions()->with('answers')->get();
        $rows = [];
        $score = 0;

        foreach ($questions as $question) {
            
            if (isset($given[$question->id])) 
            {     
                $value = $given[$question->id];      
            } 
            else 
            {
                $value = null;                   
            }

            if ($question->type === QuestionType::Text) {
                if (is_string($value)) { 
                    $text = trim($value);       
                } else {
                    $text = '';             
                }

                $answer = null;              

                if ($text !== '') {           
                    foreach ($question->answers as $accepted) {       
                        $acceptedText = mb_strtolower(trim($accepted->content));  
                        $userText = mb_strtolower($text);                      

                        if ($acceptedText === $userText) {               
                            $answer = $accepted;                         
                            break;                                      
                        }
                    }
                }

                $rows[] = [ 
                    'question_id' => $question->id, 
                    'answer_id' => $answer?->id, 
                    'text_answer' => $text !== '' ? $text : null, 
                ];
            } 
            else 
            {
                $answer = $question->answers->firstWhere('id', (int) $value); //do anwser przypisujemy odpowiedz z pytania ktorej id zgadza sie z value

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

        DB::transaction(function () use ($attempt, $rows, $score, $questions) {
            $attempt->answers()->createMany($rows);

            $attempt->update([
                'finished_at' => now(),
                'score' => $score,
                'total' => $questions->count(),
            ]);
        });

   
        if ($timeIsUp) {
            return redirect()
                ->route('attempts.result', $attempt)
                ->with('error', 'Czas minął – odpowiedzi wysłane po czasie nie zostały policzone.');
        }

        return redirect()
            ->route('attempts.result', $attempt)
            ->with('success', 'Quiz zakończony!');
    }

    public function result(Request $request, Attempt $attempt)
    {
        abort_unless($request->user()->is($attempt->user) || $request->user()->is_admin, 403);

        if (! $attempt->finished_at) {
            return redirect()->route('attempts.show', $attempt);
        }

        $attempt->load([
            'quiz',
            'answers.question.answers',  
            'answers.answer',           
        ]);
        
        $percent = $attempt->percent();

        $duration = $attempt->finished_at->timestamp - $attempt->started_at->timestamp;
        $minutes = intdiv($duration, 60);
        $seconds = $duration % 60;

        return view('attempts.result', compact('attempt', 'percent', 'minutes', 'seconds'));
    }
}