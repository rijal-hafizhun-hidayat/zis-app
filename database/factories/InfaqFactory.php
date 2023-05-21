<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infaq>
 */
class InfaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'id' => fake()->randomDigit(),
            'nama_donatur' => fake()->name(),
            'nomor_hp' => fake()->randomNumber(5, true),
            'metode_pembayaran' => 'Rekening',
            'nominal' => fake()->randomNumber(6, true),
            'bulan' => fake()->randomDigit(),
            'bukti_pembayaran' => fake()->image(null, 640, 480),
            'confirmed' => 0
        ];
    }
}
