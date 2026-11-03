@extends('layouts.admin')

@section('title', 'Edytuj quiz')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edytuj quiz: {{ $quiz->title }}</h1>

    <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}"
          class="bg-white p-6 rounded shadow max-w-lg space-y-4">
        @csrf
        @method('PUT')
        @include('admin.quizzes._form', ['buttonText' => 'Zapisz zmiany'])
    </form>
@endsection