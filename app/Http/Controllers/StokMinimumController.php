<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMinimumController extends Controller
{
    public function index()
    {
        $logs = DB::table('log_stok_minimum')
            ->join('bahan_bakus', 'bahan_bakus.id', '=', 'log_stok_minimum.bahan_baku_id')
            ->select(
                'log_stok_minimum.*',
                'bahan_bakus.name as nama_bahan',   // kolom name di tabel bahan_bakus
                'bahan_bakus.satuan_id'             // satuan_id (nanti bisa dibuat join)
            )
            ->orderBy('log_stok_minimum.created_at', 'DESC')
            ->get();

        return view('stok-minimum.index', compact('logs'));
    }
}
