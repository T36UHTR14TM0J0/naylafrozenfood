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
        // Ambil data StockProduct beserta relasi product dan supplier.
        $productStocks = StokItem::with(['item', 'supplier'])
            ->when(request('nama'), function ($query) {
                $query->whereHas('supplier', function ($query) {
                    $query->where('nama', 'like', '%' . request('nama') . '%');
                });
            })
            ->latest()
            ->paginate(10);

        // Ambil semua supplier untuk dropdown jika diperlukan
        $suppliers = Supplier::all();

        // Sertakan parameter pencarian 'nama' di link paginasi
        $productStocks->appends(['nama' => request('nama')]);

        $items = Item::with('kategori')->get(); // Sesuaikan dengan model Anda
        $categories = Kategori::all(); // Untuk filter kategori

        return view('item_stok.index', compact('productStocks', 'suppliers','items','categories'));
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
            // Create new StockItem record with validated data
            $stockItem = StokItem::create($request->validated());
            
            // Find or create StockTotal for the related item_id
            $stokTotal = StokTotal::firstOrCreate(
                ['item_id' => $stockItem->item_id],
                ['total_stok' => 0]  // Changed from 'jumlah_stok' to match your usage below
            );

            // Add the stock quantity to the total
            $stokTotal->total_stok += $stockItem->jumlah_stok;
            $stokTotal->save();

            DB::commit();

            return redirect()->route('stok.index')
                ->with('success', 'Item stock added successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
                   
            return redirect()->route('stok.index')
                ->with('error', 'Failed to add item stock. Please try again.');
        }
    }

    public function destroy($id)
    {
        // Start database transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Validate that the ID is numeric
            if (!is_numeric($id)) {
                throw new \InvalidArgumentException("Invalid ID provided");
            }

            // Find the StockProduct by ID or throw an exception
            $stockProduct = StokItem::findOrFail($id);

            // Find the related StockTotal
            $stockTotal = StokTotal::where('item_id', $stockProduct->item_id)->first();

            if ($stockTotal) {
                // Calculate new total stock
                $newTotal = $stockTotal->total_stok - $stockProduct->jumlah_stok;
                
                // Prevent negative stock
                $stockTotal->total_stok = max(0, $newTotal);
                
                // Save changes to StockTotal
                if (!$stockTotal->save()) {
                    throw new \RuntimeException("Failed to update stock total");
                }
            }

            // Delete the stock product record
            if (!$stockProduct->delete()) {
                throw new \RuntimeException("Failed to delete stock item");
            }

            // Commit transaction if all operations succeeded
            DB::commit();

            // Redirect with success message
            return redirect()->route('stok.index')
                ->with('success', 'Stock item deleted successfully');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('stok.index')
                ->with('error', 'Stock item not found');
                
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return redirect()->route('stok.index')
                ->with('error', $e->getMessage());
                
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            \Log::error('Error deleting stock item: ' . $e->getMessage());
            
            return redirect()->route('stok.index')
                ->with('error', 'Failed to delete stock item. Please try again.');
        }
    }
}
