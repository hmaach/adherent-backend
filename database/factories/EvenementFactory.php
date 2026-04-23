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
        $titres = [
            'Atelier de design UI/UX', 'Formation React.js Avancée', 'Rencontre des Freelances',
            'Séminaire Marketing Digital', 'Webinaire: Trouver des clients', 'Conférence Cyber-sécurité',
            'Masterclass: SEO et Référencement', 'Networking pour développeurs'
        ];
        $descriptions = [
            "Rejoignez-nous pour cet événement exceptionnel où nous discuterons des meilleures pratiques et tendances actuelles. Ne manquez pas cette opportunité de développer votre réseau.",
            "Une session intensive conçue pour les professionnels cherchant à améliorer leurs compétences. Places limitées, inscrivez-vous dès maintenant !",
            "Venez rencontrer d'autres experts de votre domaine pour échanger des idées, trouver des partenaires potentiels et découvrir de nouvelles opportunités."
        ];
        $titre = fake()->randomElement($titres);
        $couleur = fake()->randomElement(['red', 'blue', 'yellow', 'green']);
        $description = fake()->randomElement($descriptions);
        $dateDeb = fake()->dateTimeBetween('now', '+7 days');
        $dateFin = fake()->dateTimeBetween($dateDeb, $dateDeb->format('Y-m-d H:i:s') . ' +1 day');
        // $audience = fake()->randomElement(['public', 'etablissement', 'filiere', 'formateurs']);
        $audience = 'public';

        $user_id = function () {
            return User::where('role', 'admin')
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
