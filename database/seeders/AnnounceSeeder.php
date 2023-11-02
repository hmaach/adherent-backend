<?php

namespace Database\Seeders;

use App\Models\Announce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnounceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Announce::factory()
            ->count(30)
            ->create();
    }
}
