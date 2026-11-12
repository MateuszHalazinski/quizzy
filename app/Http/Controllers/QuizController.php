<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->integer('category') ?: null;

        $quizzes = Quiz::published()
            ->has('questions')
            ->inCategory($categoryId)
            ->with('category')
            ->withCount('questions')
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('quizzes.index', compact('quizzes', 'categories', 'categoryId'));
    }

    public function show(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->is_published, 404);

        $quiz->load('category')->loadCount('questions');

        $hasUnfinished = $request->user()->attempts()
            ->where('quiz_id', $quiz->id)
            ->unfinished()
            ->exists();

        return view('quizzes.show', compact('quiz', 'hasUnfinished'));
    }
}
