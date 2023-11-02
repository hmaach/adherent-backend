<?php

namespace Database\Factories;

use App\Models\Groupe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $password = Hash::make('0000');
        $sex = fake()->randomElement(['homme', 'femme']);
        $role = fake()->randomElement(['admin', 'formateur', 'stagiaire','adherent','user']);
        $statut = $role === 'stagiaire'
            ? fake()->randomElement(['1A', '2A', 'diplomee'])
            : null;
        $groupe_id = $role === 'stagiaire'
            ? function () {
                $groupes = Groupe::all();
                return $groupes->isNotEmpty() ? $groupes->random()->id : null;
            }
            : null;
        return [
            'nom' => fake()->firstName,
            'prenom' => fake()->lastName,
            'email' => fake()->unique()->safeEmail(),
            'tel' => fake()->unique()->phoneNumber,
            'sex' => $sex,
            'role' => $role,
            'statut' => $statut,
            'groupe_id' => $groupe_id,
            'password' => $password,
            'remember_token' => Str::random(10),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
