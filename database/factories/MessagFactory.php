<?php

namespace Database\Factories;

use App\Models\Messag;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessagFactory extends Factory
{
    protected $model = Messag::class;

    public function definition(): array
    {
        return [
            'contenu_mess' => $this->faker->text(),
            'date_mess' => $this->faker->date(),
            'conversation_id' => Conversation::factory(), // Crée une conversation pour chaque message
        ];
    }
}
