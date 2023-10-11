<?php

namespace Database\Seeders;

use App\Models\PdfCategorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PdfCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PdfCategorie::factory()
            ->count(80)
            ->create();
    }
}
