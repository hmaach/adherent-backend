<?php

namespace Database\Factories;

use App\Models\Stagiaire;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StagiaireFactory extends Factory
{
    protected $model = Stagiaire::class;

    public function definition()
    {
        return [
            'id_inscriptionsessionprogramme' => $this->faker->unique()->randomNumber(),
            'MatriculeEtudiant' => $this->faker->regexify('[A-Z]{1}[0-9]{3}'),
            'Nom' => $this->faker->lastName,
            'Prenom' => $this->faker->firstName,
            'Sexe' => $this->faker->randomElement(['Male', 'Female']),
            'EtudiantActif' => $this->faker->randomElement(['Yes', 'No']),
            'diplome' => $this->faker->word,
            'Principale' => $this->faker->jobTitle,
            'LibelleLong' => $this->faker->sentence,
            'CodeDiplome' => $this->faker->randomLetter,
            'Code' => $this->faker->word,
            'EtudiantPayant' => $this->faker->randomElement(['Yes', 'No']),
            'codediplome1' => $this->faker->randomLetter,
            'prenom2' => $this->faker->firstName,
            'DateNaissance' => $this->faker->date(),
            'Site' => $this->faker->city,
            'Regimeinscription' => $this->faker->randomElement(['Full-time', 'Part-time']),
            'DateInscription' => $this->faker->date(),
            'DateDossierComplet' => $this->faker->date(),
            'LieuNaissance' => $this->faker->city,
            'MotifAdmission' => $this->faker->sentence,
            'CIN' => $this->faker->randomNumber(8),
            'NTelelephone' => $this->faker->phoneNumber,
            'NTel_du_Tuteur' => $this->faker->phoneNumber,
            'Adresse' => $this->faker->address,
            'Nationalite' => $this->faker->country,
            'anneeEtude' => $this->faker->randomDigit,
            'Nom_Arabe' => $this->faker->firstName,
            'Prenom_arabe' => $this->faker->lastName,
            'NiveauScolaire' => $this->faker->randomElement(['Good', 'Average', 'Excellent']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
