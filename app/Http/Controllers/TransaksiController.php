<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StokItem;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Item::with(['stokTotal', 'satuan']);
        
        if ($search) {
            $query->where('nama', 'like', '%'.$search.'%');
        }
        
        $items = $query->paginate(10); // Sesuaikan jumlah item per halaman

        // Mengirim data ke view
        return view('transaksi.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'           => 'required|array',
            'item_id.*'         => 'required|exists:items,id',
            'item_price'        => 'required|array',
            'item_price.*'      => 'required|numeric|min:0',
            'item_quantity'     => 'required|array',
            'item_quantity.*'   => 'required|integer|min:1',
            'discount'          => 'nullable|numeric|min:0|max:100',
            'total_amount'      => 'required|numeric|min:0',
            'payment'           => 'required|numeric|min:0',
            'kembalian'         => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:cash,qris',
        ]);

        try {
            DB::beginTransaction();

            // Generate unique transaction ID
            $transactionId = $this->generateTransactionId();

            $transaction = Transaksi::create([
                'id'                => $transactionId, // Simpan ID yang dihasilkan
                'user_id'           => auth()->id(),
                'total_transaksi'   => $validated['total_amount'],
                'total_bayar'       => $validated['payment'],
                'kembalian'         => $validated['kembalian'] ?? 0,
                'diskon'            => $validated['discount'] ?? 0,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_transaksi' => now(),
                'status'            => 'success',
            ]);

            // Simpan detail transaksi
            foreach ($validated['item_id'] as $index => $itemId) {
                TransaksiDetail::create([
                    'transaksi_id' => $transactionId,
                    'item_id' => $itemId,
                    'jumlah' => $validated['item_quantity'][$index],
                    'total_harga' => $validated['item_price'][$index] * $validated['item_quantity'][$index],
                ]);

                // Update stok item (jika perlu)
                $item = Item::find($itemId);
                $item->stokTotal->total_stok = (int)$item->stokTotal->total_stok - (int)$validated['item_quantity'][$index];
                $item->stokTotal->save();

                $item_keluar = StokItem::create([
                    'item_id' => $itemId,
                    'jumlah_stok' => $validated['item_quantity'][$index],
                    'status'    => 'keluar'
                ]);
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'transaction_id'    => $transactionId, // Menggunakan ID yang dihasilkan
                'message'           => 'Transaksi berhasil',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function generateTransactionId()
    {
        // Format dmy
        $date = now()->format('dmy');
        
        // Ambil nomor urut terakhir dari transaksi yang ada
        $lastTransaction = Transaksi::where('id', 'like', $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        // Jika tidak ada transaksi sebelumnya, mulai dari 1
        $nextNumber = $lastTransaction ? (int)substr($lastTransaction->id, 6) + 1 : 1;

        // Format nomor urut dengan 3 digit
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return $date . $formattedNumber; // Gabungkan dmy dan nomor urut
    }

}
