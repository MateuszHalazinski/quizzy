@extends('layouts.admin')

@section('title', 'Dodaj pytanie A–D')

@section('content')
    <h1 class="text-2xl font-bold">Dodaj pytanie A–D</h1>
    <p class="text-gray-600 mb-6">Quiz: {{ $quiz->title }}</p>

    <form method="POST" action="{{ route('admin.quizzes.choice-questions.store', $quiz) }}"
          class="bg-white p-6 rounded shadow max-w-2xl space-y-4">
        @csrf
        @include('admin.choice-questions._form', ['buttonText' => 'Zapisz pytanie'])
    </form>
@endsection