<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\LogStokHabis;
use App\Models\StockHistory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBahanBaku = BahanBaku::count();
        $totalSupplier = Supplier::count();

        // Menghitung Total Stok (Jumlah semua kuantitas barang)
        $totalStokFisik = BahanBaku::sum('stock');

        // Ambil Riwayat Aktivitas Terakhir (5 Transaksi terakhir)
        $recentActivities = StockHistory::with(['bahanBaku', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        // Ambil Peringatan Stok Habis (Jika ada log baru hari ini/terbaru)
        $stokHabisLogs = LogStokHabis::with('bahanBaku')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', [
            'totalBahanBaku' => $totalBahanBaku,
            'totalSupplier' => $totalSupplier,
            'totalStokFisik' => $totalStokFisik,
            'recentActivities' => $recentActivities,
            'stokHabisLogs' => $stokHabisLogs,
        ]);
    }
}
