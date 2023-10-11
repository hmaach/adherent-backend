<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Formation>
 */
class FormationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => function () {
                return User::query()
                    ->where('role','=','stagiaire')
                    ->get()->random();
            },
            "titre" => fake()->text(30),
            "institut" => fake()->text(20),
            "dateFin" => fake()->date()
        ];
    }
}
