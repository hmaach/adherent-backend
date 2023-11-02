<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Secteur;
use App\Models\SecteurAct;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
//            FiliereSeeder::class,
//            GroupeSeeder::class,
            UserSeeder::class,
            PosteSeeder::class,
            PdfCategorieSeeder::class,
//            CvSeeder::class,
//            CoordonneesSeeder::class,
//            CompetenceSeeder::class,
//            ExperienceSeeder::class,
//            MissionSeeder::class,
//            FormationSeeder::class,
//            InteretSeeder::class,
            EvenementsSeeder::class,
//            Secteur::class,
            AdherentSeeder::class,
            AnnounceSeeder::class,
        ]);

    }
}
