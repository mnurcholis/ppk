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
        Schema::create('hutang_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('transaksi_jual_sore_id');
            $table->integer('awal');
            $table->integer('bayar');
            $table->integer('sisa');
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('transaksi_jual_sore_id')->references('id')->on('transaksi_jual_sores')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_agents');
    }
};
