<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DonasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_donatur' => fake()->name(),
            'nomor_hp' => fake()->randomNumber(5, true),
            'jenis_donasi' => 'Zakat Fitrah',
            'sha_id' => 1,
            'jumlah' => fake()->randomDigitNotNull(),
            'nominal' => fake()->randomNumber(5, true),
            'berat_beras' => 4.5,
            'metode_pembayaran' => 'Rekening',
            'bulan' => 'mei',
            'bukti_donasi' => fake()->image(null, 640, 480),
            'confirmed' => 0
        ];
    }
}
