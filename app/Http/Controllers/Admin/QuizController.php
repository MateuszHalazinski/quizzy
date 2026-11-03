<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('category')->withCount('questions')->latest()->get();

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        // Pusty quiz – potrzebny bo wspólny formularz form używa $quiz->title itd
        $quiz = new Quiz;

        $categories = Category::orderBy('name')->get();

        return view('admin.quizzes.create', compact('quiz', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['required', 'exists:categories,id'],
            'time_limit' => ['nullable', 'integer', 'min:1', 'max:180'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        Quiz::create($data);

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz został dodany.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['category', 'questions.answers']);

        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['required', 'exists:categories,id'],
            'time_limit' => ['nullable', 'integer', 'min:1', 'max:180'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        $quiz->update($data);

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Zmiany zostały zapisane.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Quiz został usunięty razem z pytaniami.');
    }
}