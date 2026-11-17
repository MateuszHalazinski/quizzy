<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AttemptController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/attempts', [AttemptController::class, 'store'])->name('attempts.store');
    Route::get('/attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
    Route::post('/attempts/{attempt}/finish', [AttemptController::class, 'finish'])->name('attempts.finish');
    Route::get('/attempts/{attempt}/result', [AttemptController::class, 'result'])->name('attempts.result');
    Route::get('/my-results', [AttemptController::class, 'index'])->name('attempts.index');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', Admin\UserController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('quizzes', Admin\QuizController::class);
    Route::resource('quizzes.choice-questions', Admin\ChoiceQuestionController::class)->parameters(['choice-questions' => 'question'])->shallow()->except(['index', 'show']);
    Route::resource('quizzes.text-questions', Admin\TextQuestionController::class)->parameters(['text-questions' => 'question'])->shallow()->except(['index', 'show']);
    Route::get('quizzes/{quiz}/results', [Admin\ResultController::class, 'index'])->name('quizzes.results');
});
