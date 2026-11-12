<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    protected $fillable = ['question_id', 'answer_id', 'text_answer'];

    // Należy do podejścia
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    // Którego pytania dotyczy
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    // Którą odpowiedź zaznaczył użytkownik (może być null)
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function isCorrect(): bool
    {
        return (bool) $this->answer?->is_correct;
    }
}