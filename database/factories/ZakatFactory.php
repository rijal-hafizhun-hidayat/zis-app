<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zakat>
 */
class ZakatFactory extends Factory
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
            'jenis_zakat' => 'Zakat Fitrah',
            'nomor_hp' => 628139378414,
            'sha_id' => 1,
            'berat_beras' => 4.5,
            'jumlah' => 1,
            'nominal' => '',
            'bulan' => 'Mei',
            'bukti_pembayaran' => fake()->image(null, 640, 480),
            'confirmed' => 0
        ];
    }
}
