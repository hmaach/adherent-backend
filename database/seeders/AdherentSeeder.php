<?php

namespace Database\Seeders;

use App\Models\Adherent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdherentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Adherent::factory()
            ->count(10)
            ->create();
    }
}
