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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('gambar');
            $table->string('nama');
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('satuan_id');
            $table->bigInteger('harga_jual');
            $table->bigInteger('harga_beli');
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
            $table->foreign('satuan_id')->references('id')->on('satuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
