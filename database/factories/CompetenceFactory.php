<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competence>
 */
class CompetenceFactory extends Factory
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
            "categorie" => fake()->text(40),
            "desc" => fake()->text(60),
        ];
    }
}
