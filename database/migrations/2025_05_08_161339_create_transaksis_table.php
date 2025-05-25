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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('faktur')->unique();
            $table->string('total_transaksi');
            $table->string('total_bayar')->nullable();
            $table->string('kembalian')->nullable();
            $table->string('diskon')->default('0');
            $table->enum('metode_pembayaran', ['cash', 'online'])->default('cash');
            $table->string('url_tautan_pembayaran')->nullable();
            $table->timestamp('tanggal_transaksi')->nullable();
            $table->enum('status', array('pending', 'success', 'expired', 'failed'))->default('pending');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
