<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokItem extends Model
{
    protected $guarded = [];

    /**
     * Relasi ke model Product.
     * Satu stock product hanya terkait dengan satu produk.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relasi ke model Supplier.
     * Satu stock product hanya terkait dengan satu supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
