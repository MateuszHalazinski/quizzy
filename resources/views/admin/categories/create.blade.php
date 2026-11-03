@extends('layouts.admin')

@section('title', 'Dodaj kategorię')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dodaj kategorię</h1>

    <form method="POST" action="{{ route('admin.categories.store') }}"
          class="bg-white p-6 rounded shadow max-w-lg space-y-4">
        @csrf
        @include('admin.categories._form', ['buttonText' => 'Zapisz'])
    </form>
@endsection