<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EvenementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = 'type';
        $titre = fake()->text(20);
        $couleur = fake()->randomElement(['red', 'blue', 'yellow', 'green']);
        $description = fake()->text(100);
        $dateDeb = fake()->dateTimeBetween('now', '+7 days'); // Generate a date within the next 7 days
        $dateFin = fake()->dateTimeBetween($dateDeb, $dateDeb->format('Y-m-d H:i:s').' +1 day'); // Generate a date within 1 day from $dateDeb
        $audience = fake()->randomElement(['public', 'etablissement', 'filiere', 'formateurs']);
        $audience_id = ($audience === 'filiere') ? fake()->numberBetween(1, 4) : null;

        $user_id = function () {
            return User::whereNull('groupe_id')
                ->inRandomOrder()
                ->value('id');
        };

        return [
            'user_id' => $user_id,
            'titre' => $titre,
            'color' => $couleur,
            'description' => $description,
            'type' => $type,
            'audience' => $audience,
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin,
        ];
    }
}
