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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_organisasi')->nullable();
            $table->string('kebutuhan');
            $table->bigInteger('nominal')->nullable();
            $table->string('jenis_dana');
            $table->string('nama_barang')->nullable();
            $table->string('bulan');
            $table->decimal('berat_beras', $precision = 8, $scale = 1)->nullable();
            $table->integer('jumlah_mustahiq')->nullable();
            $table->text('bukti_pengeluaran')->nullable();
            $table->boolean('confirmed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('pengeluaran');
    }
};
