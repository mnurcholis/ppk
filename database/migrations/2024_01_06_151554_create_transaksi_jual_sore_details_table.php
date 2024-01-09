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
        Schema::create('transaksi_jual_sore_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_jual_sore_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->integer('qty_asal');
            $table->integer('qty_keluar');
            $table->integer('qty_sisa');
            $table->integer('sub_total');
            $table->timestamps();

            $table->foreign('transaksi_jual_sore_id')->references('id')->on('transaksi_jual_sores')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_jual_sore_details');
    }
};
