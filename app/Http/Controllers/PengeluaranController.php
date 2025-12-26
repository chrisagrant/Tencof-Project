<?php

namespace App\Http\Controllers;

use App\Enum\StockTypeEnum;
use App\Models\BahanBaku;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('name')->get();
        return view('pengeluaran.create', [
            'bahanBakus' => $bahanBakus,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Cek stok dulu agar tidak minus (Validasi Logic Aplikasi)
        $bahanBaku = BahanBaku::findOrFail($request->bahan_baku_id);
        if ($bahanBaku->stock < $request->quantity) {
            return back()->with('error', "Stok tidak cukup! Sisa stok: {$bahanBaku->stock}");
        }

        // Saat baris ini dieksekusi, Trigger 'trg_update_stock_otomatis' akan menyala
        // dan mengurangi kolom 'stock' di tabel 'bahan_bakus'.
        // Jika hasil akhirnya 0, Trigger 'trg_log_barang_habis' juga akan menyala.
        StockHistory::create([
            'bahan_baku_id' => $request->bahan_baku_id,
            'type' => StockTypeEnum::OUT,
            'quantity' => $request->quantity,
            'created_by' => Auth::id(),
        ]);


        // Buat pengeluaran
StockHistory::create([
    'bahan_baku_id' => $request->bahan_baku_id,
    'type' => StockTypeEnum::OUT,
    'quantity' => $request->quantity,
    'created_by' => Auth::id(),
]);

// Ambil ulang stok SETELAH trigger jalan
$bahanBaku->refresh();

// Jika stok < 5 → munculkan popup stok minimum
if ($bahanBaku->stock < 5) {
    return redirect()->route('pengeluaran.create')
        ->with('warning', "Stok {$bahanBaku->name}");
}

return redirect()->route('stock-histories.index')
        ->with('success', 'Pengeluaran dicatat & stok berhasil dikurangi.');


        return redirect()->route('stock-histories.index')->with('success', 'Pengeluaran dicatat & Stok otomatis berkurang (via Trigger).');
    }
}
