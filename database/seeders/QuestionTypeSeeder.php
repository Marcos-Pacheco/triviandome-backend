<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionType;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionTypes = [
            ['name' => 'Múltipla Escolha'],
            ['name' => 'Verdadeiro/Falso'],
            ['name' => 'Resposta Curta'],
            ['name' => 'Numérica'],
        ];

        foreach ($questionTypes as $type) {
            QuestionType::firstOrCreate($type);
        }
    }
}

