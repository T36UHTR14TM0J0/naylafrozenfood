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

        $orderId            = $request->input('order_id');
        $statusCode         = $request->input('status_code');
        $transactionStatus  = $request->input('transaction_status');


        if ($orderId && $statusCode && $transactionStatus) {

            $transaction = Transaksi::where('faktur', $orderId)->first();

            if ($transaction) {

                if ($statusCode == 200 && $transactionStatus == 'settlement') {
                    $transaction->status = 'success';
                } elseif ($transactionStatus == 'pending') {
                    $transaction->status = 'pending';
                } elseif ($transactionStatus == 'failed') {
                    $transaction->status = 'failed';
                }
                $transaction->save();
            }


            return redirect()->route('transaksi.index');
        }

        $search = $request->input('search');
        $query  = Item::with(['stokTotal', 'satuan']);
        
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
            'metode_pembayaran' => 'required|string|in:cash,online',
        ]);

        try {
            DB::beginTransaction();
            $isOnline         = $validated['metode_pembayaran'] === 'online';

            // Validasi pembayaran tunai
            if (!$isOnline && $validated['payment'] < $validated['total_amount']) {
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
                'faktur'           => 'INV-' . now()->format('YmdHis') . strtoupper(Str::random(2)),
                'kembalian'         => $validated['kembalian'] ?? 0,
                'diskon'            => $discountAmount,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_transaksi' => now(),
                'status'            => $isOnline ? 'pending' : 'success',
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
                    'status'        => 'keluar',
                    'harga'         => $subtotal
                ]);
            }

            // Proses pembayaran Online jika dipilih
            if ($isOnline) {
                $onlineResponse = $this->createOnlineTransaction($transaction, $validated);
                
                if (!$onlineResponse['success']) {
                    throw new \Exception("Online payment failed: " . $onlineResponse['message']);
                }

                // Update transaksi dengan data Online
                $transaction->update([
                    // 'snap_token' => $onlineResponse['snap_token'],
                    'url_tautan_pembayaran' => $onlineResponse['redirect_url'] ?? null
                ]);

                DB::commit();

                return response()->json([
                    'success'           => true,
                    'transaction_id'    => $transaction->id,
                    'snap_token'        => $onlineResponse['snap_token'],
                    'redirect_url'      => $onlineResponse['redirect_url'],
                    'message'           => 'Transaksi Online berhasil, silakan selesaikan pembayaran.',
                ]);
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'faktur'         => $transaction->faktur,
                'message'        => 'Transaksi tunai berhasil',
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

    private function createOnlineTransaction($transaction, $validated)
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
                'email'         => 'kikiyulianti375@gmail.com',
            ],
            // 'enabled_payments' => ['gopay'],
            'callbacks' => [
                'finish' => route('transaksi.index')
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
                throw new \Exception("Gagal membuat transaksi Online");
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
                'message' => 'Error Online: '.$e->getMessage()
            ];
        }
    }

    
}