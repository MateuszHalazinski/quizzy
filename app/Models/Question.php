<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\QuestionType;

class Question extends Model
{
    protected $fillable = ['type', 'content'];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

   public function answers(): HasMany
    {
        return $this->hasMany(Answer::class)->orderBy('id');
    }

    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
        ];
    }
}