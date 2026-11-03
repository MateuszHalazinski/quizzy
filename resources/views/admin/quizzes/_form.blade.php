<div>
    <label for="title" class="block font-medium mb-1">Tytuł</label>
    <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}"
           class="w-full border rounded px-3 py-2">
    @error('title')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block font-medium mb-1">Opis (opcjonalnie)</label>
    <textarea id="description" name="description" rows="3"
              class="w-full border rounded px-3 py-2">{{ old('description', $quiz->description) }}</textarea>
    @error('description')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="category_id" class="block font-medium mb-1">Kategoria</label>
    <select id="category_id" name="category_id" class="w-full border rounded px-3 py-2">
        <option value="">— wybierz kategorię —</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $quiz->category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @if ($categories->isEmpty())
        <p class="text-sm mt-1">
            Brak kategorii.
            <a href="{{ route('admin.categories.create') }}" class="text-blue-600 hover:underline">Najpierw dodaj kategorię.</a>
        </p>
    @endif
    @error('category_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="time_limit" class="block font-medium mb-1">Limit czasu (minuty)</label>
    <input type="number" id="time_limit" name="time_limit" min="1" max="180"
           value="{{ old('time_limit', $quiz->time_limit) }}"
           placeholder="Puste = bez limitu"
           class="w-full border rounded px-3 py-2">
    @error('time_limit')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $quiz->is_published))>
        Opublikowany (widoczny dla użytkowników)
    </label>
</div>

<div class="flex gap-3">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        {{ $buttonText }}
    </button>
    <a href="{{ route('admin.quizzes.index') }}" class="px-4 py-2 text-gray-600 hover:underline">
        Anuluj
    </a>
</div>