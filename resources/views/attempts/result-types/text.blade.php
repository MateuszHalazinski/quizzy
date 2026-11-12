<p>
    Twoja odpowiedź:
    <span @class([
        'font-semibold',
        'text-green-700' => $row->isCorrect(),
        'text-red-700' => ! $row->isCorrect(),
    ])>
        {{ $row->text_answer ?? '—' }}
    </span>
</p>
<p class="text-sm text-gray-600 mt-1">
    Akceptowane odpowiedzi: {{ $row->question->answers->pluck('content')->join(', ') }}
</p>