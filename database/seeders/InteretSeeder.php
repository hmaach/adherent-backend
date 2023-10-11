<?php

namespace Database\Seeders;

use App\Models\Interet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InteretSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Interet::factory()
            ->count(30)
            ->create();
    }
}
