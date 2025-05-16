<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StokItem;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;

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

            // Membuat transaksi
            $transaction = Transaksi::create([
                'id'                => $transactionId,
                'user_id'           => auth()->id(),
                'total_transaksi'   => $validated['total_amount'],
                'total_bayar'       => $validated['payment'],
                'kembalian'         => $validated['kembalian'] ?? 0,
                'diskon'            => $validated['discount'] ?? 0,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_transaksi' => now(),
                'status'            => 'pending', // Status transaksi pertama adalah 'pending' jika menggunakan QRIS
            ]);

            // Simpan detail transaksi
            foreach ($validated['item_id'] as $index => $itemId) {
                TransaksiDetail::create([
                    'transaksi_id' => $transactionId,
                    'item_id' => $itemId,
                    'jumlah' => $validated['item_quantity'][$index],
                    'total_harga' => $validated['item_price'][$index] * $validated['item_quantity'][$index],
                ]);

                // Update stok item
                $item = Item::find($itemId);
                $item->stokTotal->total_stok -= (int)$validated['item_quantity'][$index];
                $item->stokTotal->save();

                // Simpan keluar stok
                StokItem::create([
                    'item_id' => $itemId,
                    'jumlah_stok' => $validated['item_quantity'][$index],
                    'status'    => 'keluar'
                ]);
            }

            // Proses pembayaran berdasarkan metode
            if ($validated['metode_pembayaran'] == 'qris') {
                // Jika metode QRIS, buat transaksi QRIS di Midtrans
                $qrisResponse = $this->createTransaction($request); // Memanggil metode untuk QRIS
                if ($qrisResponse['success']) {
                    // Mengupdate transaksi dengan status 'pending' menunggu pembayaran QRIS
                    $transaction->update(['status' => 'pending']);
                    return response()->json([
                        'success'           => true,
                        'transaction_id'    => $transactionId,
                        'snap_token'        => $qrisResponse['snap_token'], // Kembalikan token QRIS untuk frontend
                        'message'           => 'Transaksi QRIS berhasil, silakan bayar dengan QRIS.',
                    ]);
                } else {
                    throw new \Exception("QRIS payment failed: " . $qrisResponse['message']);
                }
            }

            // Jika metode pembayaran adalah Cash, update status transaksi menjadi 'success'
            if ($validated['metode_pembayaran'] == 'cash') {
                $transaction->update(['status' => 'success']);
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'transaction_id'    => $transactionId,
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



    public function createTransaction(Request $request)
    {
        // Get the server key and client key from config
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        // Initialize Midtrans API
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$clientKey = $clientKey;
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $transaction_details = array(
            'order_id' => 'ORDER-' . rand(1000, 9999),  // Unique order ID
            'gross_amount' => $request->total_transaksi,  // Total amount for the transaction
        );

        $item_details = [];
        foreach ($request->item_id as $key => $item_id) {
            $item_details[] = array(
                'id' => 'item-' . $item_id,
                'price' => $request->item_price[$key],
                'quantity' => $request->item_quantity[$key],
                'name' => $request->item_name[$key] ?? 'Unnamed Item', // Default value if null
            );
        }

        $customer_details = [
            'first_name' => 'Customer Name',
            'email' => 'customer@example.com',
            'phone' => '08123456789',
            'billing_address' => [
                'address' => 'Address',
                'city' => 'City',
                'postal_code' => 'PostalCode',
                'country_code' => 'IDN',
            ],
        ];

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
        ];

        try {
            // Generate the Snap token
            $snapToken = Snap::getSnapToken($transaction_data);

            // Check if Snap token was generated
            if (!$snapToken) {
                throw new \Exception("Snap Token could not be generated.");
            }

            return response()->json(['success' => true, 'snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }




}
