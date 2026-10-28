<?php

namespace Database\Factories;

use App\Models\Opd;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opd>
 */
class OpdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_opd' => fake()->unique()->numerify('OPD-###'),
            'nama_opd' => 'Dinas '.fake()->unique()->city(),
            'singkatan' => fake()->unique()->lexify('???'),
            'email' => fake()->unique()->safeEmail(),
            'telepon' => fake()->phoneNumber(),
            'is_active' => true,
        ];
    }
}
