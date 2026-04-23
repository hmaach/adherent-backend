<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@admin.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'nom' => 'Adherent',
            'prenom' => 'Test',
            'email' => 'adherent@adherent.com',
            'role' => 'adherent',
        ]);

        User::factory()
            ->count(100)
            ->create();
    }
}
