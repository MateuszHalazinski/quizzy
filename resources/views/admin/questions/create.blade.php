@extends('layouts.admin')

@section('title', 'Dodaj pytanie')

@section('content')
    <h1 class="text-2xl font-bold">Dodaj pytanie</h1>
    <p class="text-gray-600 mb-6">Quiz: {{ $quiz->title }}</p>

    <form method="POST" action="{{ route('admin.quizzes.questions.store', $quiz) }}"
          class="bg-white p-6 rounded shadow max-w-2xl space-y-4">
        @csrf
        @include('admin.questions._form', ['buttonText' => 'Zapisz pytanie'])
    </form>
@endsection