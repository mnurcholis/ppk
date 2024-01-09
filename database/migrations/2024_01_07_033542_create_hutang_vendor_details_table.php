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
        Schema::create('hutang_vendor_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hutang_vendor_id');
            $table->integer('bayar');
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('hutang_vendor_id')->references('id')->on('hutang_vendors')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_vendor_details');
    }
};
