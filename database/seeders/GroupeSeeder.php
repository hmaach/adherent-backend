<?php

namespace Database\Seeders;

use App\Models\Groupe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Groupe::factory()
            ->count(8)
            ->create();
    }
}
