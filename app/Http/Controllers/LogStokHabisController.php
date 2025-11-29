<?php

namespace App\Http\Controllers;

use App\Models\LogStokHabis;
use Illuminate\Http\Request;

class LogStokHabisController extends Controller
{
    public function index()
    {
        // Data ini otomatis terisi oleh Trigger database saat stok menyentuh 0
        $logs = LogStokHabis::with('bahanBaku')->latest()->paginate(15);

        return view('logs.stok_habis', [
            'logs' => $logs,
        ]);
    }
}
