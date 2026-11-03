<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $historia = Category::create(['name' => 'Historia']);

        $krolowie = $historia->quizzes()->create([
            'title' => 'Polscy królowie',
            'description' => 'Sprawdź, jak dobrze znasz władców Polski.',
            'time_limit' => 5,
            'is_published' => true,
        ]);

        $pytanie = $krolowie->questions()->create(['content' => 'Kto był pierwszym koronowanym królem Polski?']);
        $pytanie->answers()->createMany([
            ['content' => 'Bolesław Chrobry', 'is_correct' => true],
            ['content' => 'Mieszko I'],
            ['content' => 'Kazimierz Wielki'],
            ['content' => 'Władysław Łokietek'],
        ]);

        $pytanie = $krolowie->questions()->create(['content' => 'W którym roku odbyła się bitwa pod Grunwaldem?']);
        $pytanie->answers()->createMany([
            ['content' => '1410', 'is_correct' => true],
            ['content' => '1385'],
            ['content' => '1492'],
            ['content' => '1525'],
        ]);

        $pytanie = $krolowie->questions()->create(['content' => 'Kazimierz Wielki był ostatnim królem z dynastii Piastów.']);
        $pytanie->answers()->createMany([
            ['content' => 'Prawda', 'is_correct' => true],
            ['content' => 'Fałsz'],
        ]);

        $pytanie = $krolowie->questions()->create(['content' => 'Który król przeniósł stolicę z Krakowa do Warszawy?']);
        $pytanie->answers()->createMany([
            ['content' => 'Zygmunt III Waza', 'is_correct' => true],
            ['content' => 'Stefan Batory'],
            ['content' => 'Jan III Sobieski'],
        ]);

        $geografia = Category::create(['name' => 'Geografia']);

        $stolice = $geografia->quizzes()->create([
            'title' => 'Stolice Europy',
            'description' => 'Rozpoznasz stolice europejskich państw?',
            'time_limit' => null,
            'is_published' => true,
        ]);

        $pytanie = $stolice->questions()->create(['content' => 'Jaka jest stolica Francji?']);
        $pytanie->answers()->createMany([
            ['content' => 'Paryż', 'is_correct' => true],
            ['content' => 'Lyon'],
            ['content' => 'Marsylia'],
            ['content' => 'Nicea'],
        ]);

        $pytanie = $stolice->questions()->create(['content' => 'Jaka jest stolica Hiszpanii?']);
        $pytanie->answers()->createMany([
            ['content' => 'Madryt', 'is_correct' => true],
            ['content' => 'Barcelona'],
            ['content' => 'Sewilla'],
            ['content' => 'Walencja'],
        ]);

        $pytanie = $stolice->questions()->create(['content' => 'Stolicą Szwajcarii jest Zurych.']);
        $pytanie->answers()->createMany([
            ['content' => 'Prawda'],
            ['content' => 'Fałsz', 'is_correct' => true],
        ]);

        $pytanie = $stolice->questions()->create(['content' => 'Jaka jest stolica Norwegii?']);
        $pytanie->answers()->createMany([
            ['content' => 'Oslo', 'is_correct' => true],
            ['content' => 'Bergen'],
            ['content' => 'Sztokholm'],
        ]);

        $informatyka = Category::create(['name' => 'Informatyka']);

        $php = $informatyka->quizzes()->create([
            'title' => 'Podstawy PHP',
            'description' => 'Kilka pytań na rozgrzewkę z PHP.',
            'time_limit' => 10,
            'is_published' => false,
        ]);

        $pytanie = $php->questions()->create(['content' => 'Jakim znakiem zaczyna się nazwa zmiennej w PHP?']);
        $pytanie->answers()->createMany([
            ['content' => '$', 'is_correct' => true],
            ['content' => '#'],
            ['content' => '@'],
            ['content' => '&'],
        ]);

        $pytanie = $php->questions()->create(['content' => 'Który znak kończy instrukcję w PHP?']);
        $pytanie->answers()->createMany([
            ['content' => ';', 'is_correct' => true],
            ['content' => '.'],
            ['content' => ':'],
        ]);

        $pytanie = $php->questions()->create(['content' => 'PHP to język wykonywany po stronie serwera.']);
        $pytanie->answers()->createMany([
            ['content' => 'Prawda', 'is_correct' => true],
            ['content' => 'Fałsz'],
        ]);
    }
}