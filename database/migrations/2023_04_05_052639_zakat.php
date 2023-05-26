<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zakat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_donatur');
            $table->string('jenis_zakat');
            $table->string('nomor_hp');
            $table->integer('sha_id');
            $table->decimal('berat_beras', $precision = 8, $scale = 1)->nullable();
            $table->integer('jumlah')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->string('bulan');
            $table->text('bukti_pembayaran')->nullable();
            $table->boolean('confirmed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat');
    }
};
