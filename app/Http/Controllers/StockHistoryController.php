<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StockHistory::with(['bahanBaku', 'creator'])->latest();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('bahan_baku_id') && $request->bahan_baku_id != '') {
            $query->where('bahan_baku_id', $request->bahan_baku_id);
        }

        $histories = $query->paginate(15);

        $bahanBakus = BahanBaku::orderBy('name')->get();

        return view('stock_histories.index', [
            'histories' => $histories,
            'bahanBakus' => $bahanBakus,
        ]);
    }
}
