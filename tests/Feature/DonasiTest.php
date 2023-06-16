<?php

namespace Tests\Feature;

use App\Models\Donasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DonasiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_donasi_page(): void
    {
        $response = $this->get(route('donasi'));

        $response->assertOk();
    }

    public function test_store_donasi_zakat_fitrah(): void{
        $donasi = Donasi::factory()->make();

        $response = $this->post(route('donasi.store'), [
            'nama_donatur' => 'fajri',
            'nomor_hp' => 628139378414,
            'jenis_donasi' => 'Zakat Fitrah',
            'sha_id' => 2,
            'jumlah' => 3,
            'nominal' => $donasi->nominal,
            'berat_beras' => '',
            'metode_pembayaran' => $donasi->metode_pembayaran,
            'bulan' => $donasi->bulan,
            'bukti_donasi' => $donasi->bukti_donasi,
            'confirmed' => 0
        ]);


        $response->assertOk();
    }
}
