<?php

namespace Database\Seeders;

use App\Models\Poste;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Poste::factory()
            ->count(26)
            ->create();
    }
}
