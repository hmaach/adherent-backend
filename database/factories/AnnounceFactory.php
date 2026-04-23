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
            return \App\Models\Adherent::inRandomOrder()->first()->user_id;
        };

        $titres = [
            'Développement de site E-commerce', 'Création de Logo Professionnel', 'Rédaction d\'articles de blog SEO',
            'Traduction Anglais-Français', 'Montage Vidéo pour YouTube', 'Gestion de Réseaux Sociaux',
            'Consulting en Marketing', 'Création d\'applications Mobiles', 'Assistance Virtuelle', 'Photographie de Produits'
        ];
        $descriptions = [
            "Je vous propose un service professionnel, rapide et de haute qualité. Avec plus de 5 ans d'expérience dans ce domaine, je m'engage à livrer un résultat qui dépassera vos attentes.",
            "Besoin d'aide pour votre projet ? Je suis à votre disposition pour vous accompagner de A à Z. N'hésitez pas à me contacter pour discuter de vos besoins spécifiques.",
            "Une offre complète et personnalisée pour booster votre activité. Je travaille avec précision et respecte toujours les délais annoncés. Satisfaction garantie à 100%."
        ];

        return [
            'user_id' => $user_id,
            'order' => fake()->numberBetween(1, 10),
            'titre' => fake()->randomElement($titres),
            'prix' => fake()->randomElement([50, 100, 150, 200, 300, 500, 1000]),
            'approved' => fake()->boolean(90),
            'img' => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/400',
            'desc' => fake()->randomElement($descriptions),
            'debut' => $debut,
            'fin' => $fin,
        ];
    }
}
