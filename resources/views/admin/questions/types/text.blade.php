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