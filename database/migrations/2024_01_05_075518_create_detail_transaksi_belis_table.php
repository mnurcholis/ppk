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
        Schema::create('detail_transaksi_belis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_beli_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('harga_beli');
            $table->integer('qty');
            $table->integer('sub_total');
            $table->timestamps();

            $table->foreign('transaksi_beli_id')->references('id')->on('transaksi_belis')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_belis');
    }
};
