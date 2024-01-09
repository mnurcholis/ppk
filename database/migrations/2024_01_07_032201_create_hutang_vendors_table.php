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
        Schema::create('hutang_vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('transaksi_beli_id');
            $table->integer('awal');
            $table->integer('bayar');
            $table->integer('sisa');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('transaksi_beli_id')->references('id')->on('transaksi_belis')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_vendors');
    }
};
