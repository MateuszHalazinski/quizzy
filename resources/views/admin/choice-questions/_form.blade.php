@php
    $correctIndex = old('correct', $question->answers->search(fn ($answer) => $answer->is_correct));
@endphp

<div>
    <label for="content" class="block font-medium mb-1">Treść pytania</label>
    <textarea id="content" name="content" rows="3"
              class="w-full border rounded px-3 py-2">{{ old('content', $question->content) }}</textarea>
    @error('content')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<fieldset>
    <legend class="font-medium mb-1">Odpowiedzi</legend>
    <p class="text-sm text-gray-500 mb-3">
        Wpisz od 2 do 4 odpowiedzi i zaznacz poprawną. Puste pola zostaną pominięte.
    </p>

    <div class="space-y-2">
        @for ($i = 0; $i < 4; $i++)
            <div class="flex items-center gap-3">
                <input type="radio" name="correct" value="{{ $i }}"
                       @checked((string) $correctIndex === (string) $i)
                       title="Zaznacz jako poprawną">
                <span class="font-medium w-6">{{ chr(65 + $i) }})</span>
                <input type="text" name="answers[{{ $i }}]"
                       value="{{ old("answers.$i", $question->answers->get($i)?->content) }}"
                       class="flex-1 border rounded px-3 py-2">
            </div>
        @endfor
    </div>

    @error('answers')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
    @enderror
    @error('answers.*')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
    @enderror
    @error('correct')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
    @enderror
</fieldset>

<div class="flex gap-3">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        {{ $buttonText }}
    </button>
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="px-4 py-2 text-gray-600 hover:underline">
        Anuluj
    </a>
</div>