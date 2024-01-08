<?php

namespace Database\Factories;

use App\Models\Secteur;
use App\Models\SecteurAct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adherent>
 */
class AdherentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ville = fake()->randomElement(['Berkane', 'Oujda', 'Nador', 'Rabat']);

        $user_id = function () {
            return User::where('role', 'adherent')
                ->inRandomOrder()
                ->value('id');
        };
       $secteur_id = function () {
           return Secteur::inRandomOrder()
               ->value('id');
       };

        return [
            'user_id' => $user_id,
           'secteur_id' => $secteur_id,
            'propos' => fake()->text(100),
            'profession' => fake()->text(20),
            'ville' => $ville,
        ];
    }
}
