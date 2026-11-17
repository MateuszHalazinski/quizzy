@extends('layouts.admin')

@section('title', 'Dodaj pytanie z wpisaniem')

@section('content')
    <h1 class="text-2xl font-bold">Dodaj pytanie z wpisaniem</h1>
    <p class="text-gray-600 mb-6">Quiz: {{ $quiz->title }}</p>

    <form method="POST" action="{{ route('admin.quizzes.text-questions.store', $quiz) }}"
          class="bg-white p-6 rounded shadow max-w-2xl space-y-4">
        @csrf
        @include('admin.text-questions._form', ['buttonText' => 'Zapisz pytanie'])
    </form>
@endsection