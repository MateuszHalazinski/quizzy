<div class="space-y-2">
    @foreach ($question->answers as $answer)
        <label class="flex items-center gap-3 px-3 py-2 border rounded cursor-pointer hover:bg-gray-50
                      has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}">
            <span>{{ chr(64 + $loop->iteration) }}) {{ $answer->content }}</span>
        </label>
    @endforeach
</div>