<div>
    <label for="content" class="block font-medium mb-1">Treść pytania</label>
    <textarea id="content" name="content" rows="3"
              class="w-full border rounded px-3 py-2">{{ old('content', $question->content) }}</textarea>
    @error('content')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<fieldset>
    <legend class="font-medium mb-1">Akceptowane odpowiedzi</legend>
    <p class="text-sm text-gray-500 mb-3">
        Wpisz od 1 do 4 wersji poprawnej odpowiedzi, np. „dog” i „a dog”.
        Wielkość liter i spacje na początku i końcu nie mają znaczenia.
    </p>

    <div class="space-y-2">
        @for ($i = 0; $i < 4; $i++)
            <input type="text" name="answers[{{ $i }}]"
                   value="{{ old("answers.$i", $question->answers->get($i)?->content) }}"
                   class="w-full border rounded px-3 py-2">
        @endfor
    </div>

    @error('answers')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
    @enderror
    @error('answers.*')
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