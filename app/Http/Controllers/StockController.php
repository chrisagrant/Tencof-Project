<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stock\StoreStockRequest;
use App\Http\Requests\Stock\UpdateStockRequest;
use App\Models\BahanBaku;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::with(['bahanBaku', 'supplier', 'creator'])
            ->latest()
            ->paginate(10);

        return view('stocks.index', [
            'stocks' => $stocks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahanBakus = BahanBaku::all();
        $suppliers = Supplier::all();

        return view('stocks.form', [
            'bahanBakus' => $bahanBakus,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        // IMPLEMENTASI STORED PROCEDURE (sp_restock_item)
        try {
            $p_bahan_baku_id = $request->bahan_baku_id;
            $p_supplier_id   = $request->supplier_id;
            $p_quantity      = $request->quantity;
            $p_unit_price    = $request->unit_price;
            $p_user_id       = Auth::id();

            // Memanggil Stored Procedure
            DB::statement("CALL sp_restock_item(?, ?, ?, ?, ?)", [
                $p_bahan_baku_id,
                $p_supplier_id,
                $p_quantity,
                $p_unit_price,
                $p_user_id
            ]);

            return redirect()->route('stocks.index')
                ->with('success', 'Stok berhasil ditambahkan (Stored Procedure Eksekusi Sukses).');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        $bahanBakus = BahanBaku::all();
        $suppliers = Supplier::all();

        return view('stocks.form', [
            'stock' => $stock,
            'bahanBakus' => $bahanBakus,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        // Peringatan: Update ini hanya mengubah data arsip pembelian di tabel 'stocks'.
        $stock->update([
            'bahan_baku_id' => $request->bahan_baku_id,
            'supplier_id'   => $request->supplier_id,
            'quantity'      => $request->quantity,
            'unit_price'    => $request->unit_price,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Data pembelian diperbarui (Stok fisik tidak berubah).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Data pembelian dihapus.');
    }
}
