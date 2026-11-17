@extends('layouts.admin')

@section('title', 'Edytuj pytanie A–D')

@section('content')
    <h1 class="text-2xl font-bold">Edytuj pytanie A–D</h1>
    <p class="text-gray-600 mb-6">Quiz: {{ $quiz->title }}</p>

    <form method="POST" action="{{ route('admin.choice-questions.update', $question) }}"
          class="bg-white p-6 rounded shadow max-w-2xl space-y-4">
        @csrf
        @method('PUT')
        @include('admin.choice-questions._form', ['buttonText' => 'Zapisz zmiany'])
    </form>
@endsection