<ul class="space-y-2">
    @foreach ($row->question->answers as $option)
        <li @class([
            'px-3 py-2 rounded border',
            'bg-green-50 border-green-400 text-green-800' => $option->is_correct,
            'bg-red-50 border-red-400 text-red-800' => $option->id === $row->answer_id && ! $option->is_correct,
            'border-gray-200' => ! $option->is_correct && $option->id !== $row->answer_id,
        ])>
            {{ chr(64 + $loop->iteration) }}) {{ $option->content }}

            @if ($option->is_correct)
                <span class="ml-2">✓ poprawna</span>
            @endif
            @if ($option->id === $row->answer_id)
                <span class="ml-2 font-semibold">← Twoja odpowiedź</span>
            @endif
        </li>
    @endforeach
</ul>