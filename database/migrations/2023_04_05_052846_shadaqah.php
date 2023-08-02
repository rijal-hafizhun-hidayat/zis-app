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
        Schema::create('shadaqah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_donatur');
            $table->string('nomor_hp');
            $table->string('jenis_bantuan');
            $table->string('keterangan')->nullable();
            $table->integer('jumlah');
            $table->string('bulan');
            $table->bigInteger('nominal')->nullable();
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
        Schema::dropIfExists('shadaqah');
    }
};
