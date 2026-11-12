<input type="hidden" name="type" value="{{ $question->type->value }}">

<p class="text-sm text-gray-500">Typ pytania: <strong>{{ $question->type->label() }}</strong></p>

<div>
    <label for="content" class="block font-medium mb-1">Treść pytania</label>
    <textarea id="content" name="content" rows="3"
              class="w-full border rounded px-3 py-2">{{ old('content', $question->content) }}</textarea>
    @error('content')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Część zależna od typu pytania --}}
@include('admin.questions.types.' . $question->type->value)

<div class="flex gap-3">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        {{ $buttonText }}
    </button>
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="px-4 py-2 text-gray-600 hover:underline">
        Anuluj
    </a>
</div>