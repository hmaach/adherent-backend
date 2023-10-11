<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cv>
 */
class CvFactory extends Factory
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
                $stagiairesWithoutCV = User::query()
                    ->where('role', '=', 'stagiaire')
                    ->whereDoesntHave('cv')
                    ->get();

                if ($stagiairesWithoutCV->isEmpty()) {
                    return null;
                }

                return $stagiairesWithoutCV->random();
            },
            "propos" => fake()->text(200),
            "intimite" => fake()->boolean,
            "dateNais" => fake()->date()
        ];
    }

}
