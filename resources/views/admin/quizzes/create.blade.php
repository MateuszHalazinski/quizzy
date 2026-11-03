@extends('layouts.admin')

@section('title', 'Dodaj quiz')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dodaj quiz</h1>

    <form method="POST" action="{{ route('admin.quizzes.store') }}"
          class="bg-white p-6 rounded shadow max-w-lg space-y-4">
        @csrf
        @include('admin.quizzes._form', ['buttonText' => 'Zapisz'])
    </form>
@endsection