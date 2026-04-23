<?php

namespace Database\Seeders;

use App\Models\Secteur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $secteurs = [
            'Plomberie & Chauffage',
            'Électricité & Éclairage',
            'Agriculture & Machinerie Agricole',
            'Informatique & Développement Web',
            'Soutien Scolaire & Enseignement',
            'Menuiserie & Charpente',
            'Climatisation & Froid',
            'Design & Décoration',
            'Photographie & Événementiel',
            'Transport & Logistique',
            'Bâtiment & Maçonnerie',
            'Esthétique & Coiffure',
            'Mécanique Automobile',
            'Nettoyage & Entretien',
            'Jardinage & Aménagement Paysager'
        ];

        foreach ($secteurs as $secteur) {
            Secteur::firstOrCreate(['lib' => $secteur]);
        }
    }
}
