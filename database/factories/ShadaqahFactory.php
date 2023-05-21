<?php

namespace Database\Factories;

use App\Models\Shadaqah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shadaqah>
 */
class ShadaqahFactory extends Factory
{
    protected $model = Shadaqah::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->randomDigit(),
            'nama_donatur' => fake()->name(),
            'nomor_hp' => fake()->randomNumber(5, true),
            'jenis_bantuan' => 'Barang',
            'keterangan' => fake()->word(4, true),
            'bulan' => fake()->randomDigit(),
            'nominal' => fake()->randomNumber(6, true),
            'bukti_pembayaran' => fake()->image(null, 640, 480),
            'confirmed' => 0
        ];
    }
}
