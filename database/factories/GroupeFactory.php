<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Filiere;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Groupe>
 */
class GroupeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ext = $this->faker->randomElement(
            [
                'DEV',
                'ESA',
                'GE',
                "TFI"
            ]
        );

        $prefix = strtoupper($ext);
        $suffixes = ['101', '102', '201', '202'];
        $groups = array_map(function($suffix) use ($prefix) {
            return $prefix . $suffix;
        }, $suffixes);

        return [
            'libelle' => $this->faker->randomElement($groups),
            'filiere_id' => function() use ($ext) {
                return Filiere::where('extention', $ext)->first()->id;
            },
            'dateDeb' => $this->faker->date(),
            'dateFin' => $this->faker->date(),
        ];
    }
}
