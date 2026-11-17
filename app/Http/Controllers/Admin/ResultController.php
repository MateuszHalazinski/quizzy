<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;

class ResultController extends Controller
{
    // GET /admin/quizzes/{quiz}/results – wszystkie zakończone podejścia do quizu
    public function index(Quiz $quiz)
    {
        $attempts = $quiz->attempts()
            ->finished()
            ->with('user')
            ->latest('finished_at')
            ->get();

        $average = $attempts->isNotEmpty()
            ? (int) round($attempts->avg(fn ($attempt) => $attempt->percent()))
            : null;

        $best = $attempts->max(fn ($attempt) => $attempt->percent());

        return view('admin.results.index', compact('quiz', 'attempts', 'average', 'best'));
    }
}
