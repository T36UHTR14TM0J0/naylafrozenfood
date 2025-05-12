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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaksi_id'); // Sesuai dengan tipe di transaksis
            $table->foreign('transaksi_id')
                ->references('id')
                ->on('transaksis')
                ->onDelete('cascade');
            
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->integer('jumlah');
            $table->bigInteger('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
