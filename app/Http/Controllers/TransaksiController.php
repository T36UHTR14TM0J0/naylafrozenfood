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
use Illuminate\Support\Str;
use Midtrans\Config;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Item::with(['stokTotal', 'satuan']);
        
        if ($search) {
            $query->where('nama', 'like', '%'.$search.'%');
        }
        
        $items = $query->paginate(10);
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
            $isQris         = $validated['metode_pembayaran'] === 'qris';

            // Validasi pembayaran tunai
            if (!$isQris && $validated['payment'] < $validated['total_amount']) {
                throw new \Exception("Pembayaran tunai tidak mencukupi total transaksi");
            }

            // Hitung diskon jika ada
            $discountAmount = 0;
            if (!empty($validated['discount'])) {
                $discountAmount = ($validated['total_amount'] * $validated['discount']) / 100;
            }

            // Membuat transaksi
            $transaction = Transaksi::create([
                'user_id'           => auth()->id(),
                'total_transaksi'   => $validated['total_amount'],
                'total_bayar'       => $validated['payment'],
                 'faktur'           => 'INV-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5)),
                'kembalian'         => $validated['kembalian'] ?? 0,
                'diskon'            => $discountAmount,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_transaksi' => now(),
                'status'            => $isQris ? 'pending' : 'success',
            ]);

            // Simpan detail transaksi
            foreach ($validated['item_id'] as $index => $itemId) {
                $item = Item::with('stokTotal')->findOrFail($itemId);
                
                // Validasi stok
                if ($item->stokTotal->total_stok < $validated['item_quantity'][$index]) {
                    throw new \Exception("Stok item {$item->nama} tidak mencukupi");
                }

                $subtotal = $validated['item_price'][$index] * $validated['item_quantity'][$index];
                
                TransaksiDetail::create([
                    'transaksi_id'  => $transaction->id,
                    'item_id'       => $itemId,
                    'jumlah'        => $validated['item_quantity'][$index],
                    'total_harga'   => $subtotal,
                ]);

                // Update stok item
                $item->stokTotal->total_stok -= (int)$validated['item_quantity'][$index];
                $item->stokTotal->save();

                // Simpan keluar stok
                StokItem::create([
                    'item_id'       => $itemId,
                    'jumlah_stok'   => $validated['item_quantity'][$index],
                    'status'        => 'keluar'
                ]);
            }

            // Proses pembayaran QRIS jika dipilih
            if ($isQris) {
                $qrisResponse = $this->createQrisTransaction($transaction, $validated);
                
                if (!$qrisResponse['success']) {
                    throw new \Exception("QRIS payment failed: " . $qrisResponse['message']);
                }

                // Update transaksi dengan data QRIS
                $transaction->update([
                    // 'snap_token' => $qrisResponse['snap_token'],
                    'url_tautan_pembayaran' => $qrisResponse['redirect_url'] ?? null
                ]);

                DB::commit();

                return response()->json([
                    'success'        => true,
                    'transaction_id' => $transaction->id,
                    'snap_token'    => $qrisResponse['snap_token'],
                    'redirect_url'  => $qrisResponse['redirect_url'],
                    'message'      => 'Transaksi QRIS berhasil, silakan selesaikan pembayaran.',
                ]);
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'transaction_id' => $transaction->id,
                'message'       => 'Transaksi tunai berhasil',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Error: ' . $e->getMessage(), [
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function createQrisTransaction($transaction, $validated)
    {

        // dd($transaction);
        // die;
        // Validasi konfigurasi Midtrans
        if (empty(config('midtrans.server_key')) || empty(config('midtrans.client_key'))) {
            throw new \Exception("Konfigurasi pembayaran tidak valid");
        }

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // Siapkan item details
        $item_details = [];
        $totalCalculated = 0;
        
        foreach ($validated['item_id'] as $index => $itemId) {
            $item = Item::findOrFail($itemId);
            $itemTotal = $validated['item_price'][$index] * $validated['item_quantity'][$index];
            
            $item_details[] = [
                'id'       => 'item-'.$itemId,
                'price'    => $validated['item_price'][$index],
                'quantity' => $validated['item_quantity'][$index],
                'name'     => $item->nama,
            ];
            
            $totalCalculated += $itemTotal;
        }

        // Tambahkan diskon sebagai item negatif jika ada
        if ($transaction->diskon > 0) {
            $item_details[] = [
                'id'       => 'discount',
                'price'    => -$transaction->diskon,
                'quantity' => 1,
                'name'    => 'Diskon',
            ];
        }

        // Data transaksi untuk Midtrans
        $transaction_data = [
            'transaction_details' => [
                'order_id'     => $transaction->faktur,
                'gross_amount' => $validated['total_amount'] - $transaction->diskon,
            ],
            'item_details' => $item_details,
            'customer_details'  => [
                'first_name'    => 'Customer',
                'email'         => 'teguhtriatmojo23@gmail.com',
            ],
            'enabled_payments' => ['gopay'],
            'callbacks' => [
                'finish' => route('transaksi.callback')
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit'      => 'minutes',
                'duration'  => 30
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($transaction_data);
            
            if (empty($snapToken)) {
                throw new \Exception("Gagal membuat transaksi QRIS");
            }

            return [
                'success'     => true,
                'snap_token' => $snapToken,
                'redirect_url' => config('midtrans.is_production') 
                    ? 'https://app.midtrans.com/snap/v2/vtweb/'.$snapToken
                    : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/'.$snapToken,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Error: '.$e->getMessage(), [
                'transaction' => $transaction->id,
                'error'      => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error QRIS: '.$e->getMessage()
            ];
        }
    }



    // Callback handler untuk Midtrans
    public function handleCallback(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        $transaction = Transaksi::find($orderId);
        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        if ($transaction->status !== 'pending') {
            return response()->json(['status' => 'success', 'message' => 'Transaction already processed']);
        }

        if ($statusCode == 200 && $transactionStatus == 'settlement') {
            $transaction->update(['status' => 'success']);
            return response()->json(['status' => 'success', 'message' => 'Payment successful']);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $transaction->update(['status' => 'failed']);
            return response()->json(['status' => 'failed', 'message' => 'Payment failed']);
        }

        return response()->json(['status' => 'pending', 'message' => 'Waiting for payment']);
    }
}