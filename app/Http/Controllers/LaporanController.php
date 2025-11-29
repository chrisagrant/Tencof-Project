<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function stok()
    {
        // MEMANGGIL DATABASE VIEW (view_stock_details)
        $laporan = DB::table('view_stock_details')
            ->orderBy('nama_barang', 'asc')
            ->paginate(15);

        return view('laporan.stok', [
            'laporan' => $laporan,
        ]);
    }
}
