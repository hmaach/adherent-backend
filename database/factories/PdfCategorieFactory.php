<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PdfCategorie>
 */
class PdfCategorieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => function () {
                return User::query()
                    ->where('role','=','admin')
                    ->orWhere('role','=','formateur')
                    ->get()->random();
            },
            "label" => fake()->text(20)
        ];
    }
}
