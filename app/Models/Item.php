<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];


    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }


    /**
     * Relasi ke model Category.
     * Satu produk hanya memiliki satu kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke model StockTotal.
     * Satu produk memiliki satu total stok.
     */
    public function stokTotal()
    {
        return $this->hasOne(StokTotal::class);
    }

    /**
     * Aksesors untuk mendapatkan URL gambar produk.
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/products/' . $image),
        );
    }
}
