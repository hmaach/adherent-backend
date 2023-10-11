<?php

namespace Database\Seeders;

use App\Models\Coordonnees;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoordonneesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coordonnees::factory()
            ->count(30)
            ->create();
    }
}
