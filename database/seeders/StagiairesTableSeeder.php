<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stagiaire;

class StagiairesTableSeeder extends Seeder
{
    public function run()
    {
        Stagiaire::factory()->count(10)->create();
    }
}
