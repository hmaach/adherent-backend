<?php

namespace Database\Seeders;

use App\Models\Mission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mission::factory()
            ->count(30)
            ->create();
    }
}
