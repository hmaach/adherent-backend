<?php

namespace Database\Seeders;

use App\Models\Evenement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvenementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Evenement::factory()
            ->count(40)
            ->create();
    }
}
