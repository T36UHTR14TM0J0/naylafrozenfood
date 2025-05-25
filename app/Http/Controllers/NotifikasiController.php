<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
class NotifikasiController extends Controller
{
  
    public function paymentNotification(Request $request)
    {
        // Ambil payload dari request
        $payload        = $request->getContent();
        $notification   = json_decode($payload);

        // Verifikasi signature untuk memastikan keamanan
        $signatureKey = hash('sha512',
            $notification->order_id .
            $notification->status_code .
            $notification->gross_amount .
            config('midtrans.server_key')
        );

        if ($notification->signature_key !== $signatureKey) {
            return;
        }

        // Ambil data status transaksi dan tipe pembayaran
        $transactionStatus  = $notification->transaction_status;
        $paymentType        = $notification->payment_type;
        $orderId            = $notification->order_id;

        // Cari transaksi berdasarkan invoice yang sama dengan order_id
        $transaction = Transaksi::where('faktur', $orderId)->first();

        if (!$transaction) {
            return;
        }

        // Tentukan status transaksi berdasarkan status dari Midtrans
        switch ($transactionStatus) {
            case 'capture':
                $transaction->update([
                    'status' => 'success',
                    'metode_pembayaran' => 'online',
                ]);
                break;

            case 'settlement':
                $transaction->update([
                    'status' => 'success',
                    'metode_pembayaran' => 'online',
                ]);
                break;

            case 'pending':
                $transaction->update([
                    'status' => 'pending',
                    'metode_pembayaran' => 'online',
                ]);
                break;

            case 'deny':
                $transaction->update([
                    'status' => 'failed',
                    'metode_pembayaran' => 'online',
                ]);
                break;

            case 'expire':
                $transaction->update([
                    'status' => 'expired',
                    'metode_pembayaran' => 'online',
                ]);
                break;

            case 'cancel':
                $transaction->update([
                    'status' => 'failed',
                    'metode_pembayaran' => 'online',
                ]);
                break;

            default:
                return;
        }

    }


}
