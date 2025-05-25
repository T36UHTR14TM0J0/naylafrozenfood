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
    /**
     * Menampilkan daftar stok item dengan filter
     */
    public function index()
    {
        // Tentukan default tanggal jika tanggal_awal atau tanggal_akhir tidak ada dalam request
        $tanggalAwal = request('tanggal_awal') ?: now()->format('Y-m-d'); // Jika tidak ada tanggal_awal, set ke hari ini
        $tanggalAkhir = request('tanggal_akhir') ?: now()->addDays(30)->format('Y-m-d'); // Jika tidak ada tanggal_akhir, set ke 30 hari ke depan

        // Ambil data StokItem beserta relasi item dan supplier
        $productStocks = StokItem::with(['item', 'supplier'])
            ->when($tanggalAwal && $tanggalAkhir, function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]); // Filter berdasarkan rentang tanggal
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status')); // Filter berdasarkan status (masuk/keluar)
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

        // Kembalikan view dengan data terkait
        return view('item_stok.index', compact('productStocks', 'suppliers', 'items', 'categories'));
    }

    /**
     * Menampilkan form untuk menambah stok item
     */
    public function create(Request $request)
    {
        // Ambil semua item dan supplier untuk dropdown
        $items = Item::all();
        $suppliers = Supplier::all();

        // Kembalikan view dengan data item dan supplier
        return view('item_stok.create', compact('items', 'suppliers'));
    }

    /**
     * Menyimpan stok item yang baru
     */
    public function store(ItemStockRequest $request)
    {
        // Memulai transaksi database untuk menjaga konsistensi data
        DB::beginTransaction();

        try {
            // Membuat record StokItem baru dengan data yang telah divalidasi
            $stockItem = StokItem::create($request->validated());
            
            // Mencari atau membuat StokTotal untuk item_id yang terkait
            $stokTotal = StokTotal::firstOrCreate(
                ['item_id' => $stockItem->item_id],
                ['total_stok' => 0] // Jika belum ada, buat dengan stok total 0
            );

            // Menambahkan jumlah stok ke total stok
            $stokTotal->total_stok += $stockItem->jumlah_stok;
            $stokTotal->save(); // Simpan perubahan stok total

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Redirect ke halaman stok dengan pesan sukses
            return redirect()->route('stok.index')
                ->with('success', 'Stok item berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
                
            // Redirect kembali dengan pesan error
            return redirect()->route('stok.index')
                ->with('error', 'Gagal menambahkan stok item. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus stok item berdasarkan ID
     */
    public function destroy($id)
    {
        // Memulai transaksi database untuk menjaga konsistensi data
        DB::beginTransaction();

        try {
            // Validasi bahwa ID adalah numerik
            if (!is_numeric($id)) {
                throw new \InvalidArgumentException("ID yang diberikan tidak valid");
            }

            // Mencari StokItem berdasarkan ID atau melemparkan pengecualian
            $stockProduct = StokItem::findOrFail($id);

            // Mencari StokTotal yang terkait dengan item_id
            $stockTotal = StokTotal::where('item_id', $stockProduct->item_id)->first();

            if ($stockTotal) {
                // Menghitung stok total yang baru setelah pengurangan
                $newTotal = $stockTotal->total_stok - $stockProduct->jumlah_stok;
                
                // Menghindari stok negatif
                $stockTotal->total_stok = max(0, $newTotal);
                
                // Menyimpan perubahan stok total
                if (!$stockTotal->save()) {
                    throw new \RuntimeException("Gagal memperbarui total stok");
                }
            }

            // Menghapus stok item
            if (!$stockProduct->delete()) {
                throw new \RuntimeException("Gagal menghapus stok item");
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('stok.index')
                ->with('success', 'Stok item berhasil dihapus');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            return redirect()->route('stok.index')
                ->with('error', 'Stok item tidak ditemukan');
                
        } catch (\InvalidArgumentException $e) {
            // Rollback jika ID tidak valid
            DB::rollBack();
            return redirect()->route('stok.index')
                ->with('error', $e->getMessage());
                
        } catch (\Exception $e) {
            // Rollback dan log kesalahan untuk debugging
            DB::rollBack();
            \Log::error('Error deleting stock item: ' . $e->getMessage());
            
            return redirect()->route('stok.index')
                ->with('error', 'Gagal menghapus stok item. Silakan coba lagi.');
        }
    }
}
