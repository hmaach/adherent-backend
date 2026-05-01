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

        $libelles = [
            "Quelqu'un aurait-il des conseils pour améliorer le référencement naturel d'un nouveau site ?",
            "Recherche un développeur React.js pour une mission courte le week-end prochain. Envoyez-moi un message !",
            "Quels sont vos outils préférés pour gérer les réseaux sociaux de vos clients ?",
            "Je viens de publier un nouveau service de design de logo. N'hésitez pas à jeter un œil à mon profil !",
            "Est-ce que quelqu'un a de l'expérience avec la configuration de serveurs Linux sur AWS ? J'ai un petit souci.",
            "Partage d'expérience : Comment j'ai réussi à trouver mes premiers clients en tant que freelance web designer."
        ];

        return [
            'user_id' => $user_id,
            'libelle' => fake()->randomElement($libelles),
            'type' => $type,
            'audience' => $audience,
            'audience_id' => $audience_id,
        ];
    }
}
