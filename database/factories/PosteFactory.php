<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PosteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['announce', 'cour', 'exercice']);
        $audience = fake()->randomElement(['public', 'etablissement', 'filiere', 'formateurs']);
        $audience_id = ($audience === 'filiere') ? fake()->numberBetween(1, 4) : null;

        $user_id = function () {
            return User::whereNull('groupe_id')
                ->inRandomOrder()
                ->value('id');
        };

        return [
            'user_id' => $user_id,
            'libelle' => fake()->text(100),
            'type' => $type,
            'audience' => $audience,
            'audience_id' => $audience_id,
        ];
    }
}
