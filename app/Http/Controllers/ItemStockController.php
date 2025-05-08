<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\StokItem;
use App\Http\Requests\ItemStockRequest;
use App\Models\Kategori;
use App\Models\StokTotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemStockController extends Controller
{
    public function index()
    {
        // Tentukan default tanggal jika tanggal_awal atau tanggal_akhir tidak ada dalam request
        $tanggalAwal = request('tanggal_awal') ?: now()->format('Y-m-d'); // Jika tidak ada tanggal_awal, set ke hari ini
        $tanggalAkhir = request('tanggal_akhir') ?: now()->addDays(30)->format('Y-m-d'); // Jika tidak ada tanggal_akhir, set ke 30 hari ke depan

        // Ambil data StokItem beserta relasi item dan supplier
        $productStocks = StokItem::with(['item', 'supplier'])
            ->when($tanggalAwal && $tanggalAkhir, function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan tanggal terbaru
            ->paginate(10);

        // Ambil semua supplier untuk dropdown jika diperlukan
        $suppliers = Supplier::all();

        // Sertakan parameter pencarian 'nama' di link paginasi
        $productStocks->appends([
            'nama' => request('nama'),
            'tanggal_awal' => $tanggalAwal, 
            'tanggal_akhir' => $tanggalAkhir, 
            'status' => request('status')
        ]);

        // Ambil semua item untuk dropdown kategori
        $items = Item::with('kategori')->get();
        $categories = Kategori::all();

        return view('item_stok.index', compact('productStocks', 'suppliers', 'items', 'categories'));
    }



    public function create(Request $request)
    {
        $items      = Item::all();
        $suppliers  = Supplier::all();

        return view('item_stok.create', compact('items', 'suppliers'));
    }

    public function store(ItemStockRequest $request)
    {
        DB::beginTransaction();

        try {
            // Membuat record StokItem baru dengan data yang telah divalidasi
            $stockItem = StokItem::create($request->validated());
            
            // Mencari atau membuat StokTotal untuk item_id yang terkait
            $stokTotal = StokTotal::firstOrCreate(
                ['item_id' => $stockItem->item_id],
                ['total_stok' => 0]  // Mengubah dari 'jumlah_stok' agar sesuai dengan penggunaan Anda di bawah
            );

            // Menambahkan jumlah stok ke total stok
            $stokTotal->total_stok += $stockItem->jumlah_stok;
            $stokTotal->save();

            DB::commit();

            return redirect()->route('stok.index')
                ->with('success', 'Stok item berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
                
            return redirect()->route('stok.index')
                ->with('error', 'Gagal menambahkan stok item. Silakan coba lagi.');
        }
    }


    public function destroy($id)
    {
        // Memulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();

        try {
            // Validasi bahwa ID adalah numerik
            if (!is_numeric($id)) {
                throw new \InvalidArgumentException("ID yang diberikan tidak valid");
            }

            // Mencari StokItem berdasarkan ID atau melemparkan pengecualian
            $stockProduct = StokItem::findOrFail($id);

            // Mencari StokTotal yang terkait
            $stockTotal = StokTotal::where('item_id', $stockProduct->item_id)->first();

            if ($stockTotal) {
                // Menghitung stok total yang baru
                $newTotal = $stockTotal->total_stok - $stockProduct->jumlah_stok;
                
                // Menghindari stok negatif
                $stockTotal->total_stok = max(0, $newTotal);
                
                // Menyimpan perubahan ke StokTotal
                if (!$stockTotal->save()) {
                    throw new \RuntimeException("Gagal memperbarui total stok");
                }
            }

            // Menghapus record stok item
            if (!$stockProduct->delete()) {
                throw new \RuntimeException("Gagal menghapus stok item");
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('stok.index')
                ->with('success', 'Stok item berhasil dihapus');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('stok.index')
                ->with('error', 'Stok item tidak ditemukan');
                
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return redirect()->route('stok.index')
                ->with('error', $e->getMessage());
                
        } catch (\Exception $e) {
            DB::rollBack();
            // Menyimpan log kesalahan untuk keperluan debug
            \Log::error('Error deleting stock item: ' . $e->getMessage());
            
            return redirect()->route('stok.index')
                ->with('error', 'Gagal menghapus stok item. Silakan coba lagi.');
        }
    }

}
