<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengeluaran>
 */
class PengeluaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_organisasi' => fake()->name(),
            'kebutuhan' => fake()->word(),
            'jenis_dana' => 'Zakat',
            'berat_beras' => fake()->randomFloat(1, 20, 30),
            'jumlah_mustahiq' => fake()->randomNumber(2, true),
            'nominal' => fake()->randomNumber(5, true),
            'bulan' => fake()->randomDigit(),
            'bukti_pengeluaran' => fake()->image(null, 640, 480),
            'confirmed' => 0
        ];
    }
}
