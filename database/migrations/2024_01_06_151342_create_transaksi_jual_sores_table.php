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
        Schema::create('transaksi_jual_sores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_jual_pagi_id');
            $table->unsignedBigInteger('agent_id');
            $table->string('invoice');
            $table->date('tanggal');
            $table->integer('total');
            $table->integer('bayar');
            $table->integer('sisa');
            $table->timestamps();

            $table->foreign('transaksi_jual_pagi_id')->references('id')->on('transaksi_jual_pagis')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('agent_id')->references('id')->on('agents')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_jual_sores');
    }
};
