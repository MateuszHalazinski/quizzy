<div>
    <label for="name" class="block font-medium mb-1">Nazwa kategorii</label>
    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
           class="w-full border rounded px-3 py-2">
    @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex gap-3">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        {{ $buttonText }}
    </button>
    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-gray-600 hover:underline">
        Anuluj
    </a>
</div>