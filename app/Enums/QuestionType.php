<?php

namespace App\Enums;

enum QuestionType: string
{
    case SingleChoice = 'single_choice';
    case Text = 'text';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => 'Jednokrotny wybór (A–D)',
            self::Text => 'Wpisanie odpowiedzi',
        };
    }
}