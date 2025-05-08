<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Transaksi extends Model
{
    protected $guarded = [];

  

    // Relasi dengan model TransactionDetail
    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    // Event boot untuk logika tambahan saat model di-create
    protected static function boot()
    {
        parent::boot();

        // Menambahkan logika khusus saat transaksi dibuat
        static::creating(function ($transaksi) {
            // Menghasilkan invoice dengan format INV-YYYYMMDDHHMMSS-RANDOM5
            $transaksi->faktur = 'INV-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));

            // Menetapkan tanggal transaksi jika belum diisi
            if (empty($transaksi->tanggal_transaksi)) {
                $transaksi->tanggal_transaksi = Carbon::now();
            }
        });
    }
}
