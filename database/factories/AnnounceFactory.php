<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announce>
 */
class AnnounceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $debut = fake()->dateTimeThisCentury;
        $fin = (new Carbon($debut))->addMonth();
        $user_id = function () {
            return User::where('role', 'adherent')
                ->inRandomOrder()
                ->value('id');
        };

        return [
            'user_id' => $user_id,
            'order' => fake()->numberBetween(1, 10),
            'img' => fake()->imageUrl(),
            'desc' => fake()->paragraph,
            'debut' => $debut,
            'fin' => $fin,
        ];
    }
}
