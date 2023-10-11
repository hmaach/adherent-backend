<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Filiere>
 */
class FiliereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $libelle = $this->faker->unique()->randomElement(
            [
                'développement digital',
                'électromécanique des systèmes automatisés',
                'gestion des entreprises',
                "froid industriel",
            ]
        );

        $niveau = $libelle === "Froid industriel" ? 't'
            : 'ts'
        ;
        if ($libelle === "développement digital")
            $ext = 'DEV';
        elseif ($libelle === "électromécanique des systèmes automatisés")
            $ext = 'ESA';
        elseif ($libelle === "gestion des entreprises")
            $ext = 'GE';
        elseif ($libelle === "petite enface")
            $ext = 'ESPE';
        elseif ($libelle === "froid industriel")
            $ext = 'TFI';


        return [
            'libelle' => $libelle,
            'niveau' => $niveau,
            'extention'=>$ext
        ];
    }
}
