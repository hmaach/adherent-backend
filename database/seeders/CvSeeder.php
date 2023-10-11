<?php

namespace Database\Seeders;

use App\Models\CV;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CV::factory()
        ->count(20)
        ->create();
    }
}
