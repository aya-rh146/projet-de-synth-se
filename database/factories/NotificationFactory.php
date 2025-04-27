<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'contenu_noti' => $this->faker->text(),
            'type' => $this->faker->randomElement(['reservation', 'message', 'systeme']),
            'date_noti' => $this->faker->date(),
            'utilisateur_id' => Utilisateur::factory(),
        ];
    }
}
