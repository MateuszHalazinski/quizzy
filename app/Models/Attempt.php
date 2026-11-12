<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Attempt extends Model
{
    protected $fillable = ['quiz_id', 'started_at', 'finished_at', 'score', 'total'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    // Podejście należy do użytkownika
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Podejście dotyczy quizu
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Podejście ma wiele udzielonych odpowiedzi
    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function scopeFinished(Builder $query): void
    {
        $query->whereNotNull('finished_at');
    }

    public function scopeUnfinished(Builder $query): void
    {
        $query->whereNull('finished_at');
    }

    public function percent(): int
    {
        return $this->total > 0
            ? (int) round($this->score / $this->total * 100)
            : 0;
    }
}