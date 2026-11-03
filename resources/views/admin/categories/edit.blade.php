@extends('layouts.admin')

@section('title', 'Edytuj kategorię')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edytuj kategorię: {{ $category->name }}</h1>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}"
          class="bg-white p-6 rounded shadow max-w-lg space-y-4">
        @csrf
        @method('PUT')
        @include('admin.categories._form', ['buttonText' => 'Zapisz zmiany'])
    </form>
@endsection