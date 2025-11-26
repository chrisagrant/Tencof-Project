<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use App\Http\Requests\StoreStockHistoryRequest;
use App\Http\Requests\UpdateStockHistoryRequest;

class StockHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stockHistories = StockHistory::with('bahanBaku')->get();
            return response()->json(['data' => $stockHistories], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockHistoryRequest $request)
    {
        try {
            $stockHistory = StockHistory::create([
                'bahan_baku_id' => $request->bahan_baku_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'created_by' => auth()->id()
            ]);
            return response()->json(['message' => 'Stock History created successfully', 'data' => $stockHistory], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockHistory $stockHistory)
    {
        try {
            $stockHistory->load('bahanBaku');
            return response()->json(['data' => $stockHistory], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockHistoryRequest $request, StockHistory $stockHistory)
    {
        try {
            $stockHistory->update($request->only(['bahan_baku_id', 'type', 'quantity']));
            return response()->json(['message' => 'Stock History updated successfully', 'data' => $stockHistory], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockHistory $stockHistory)
    {
        try {
            $stockHistory->delete();
            return response()->json(['message' => 'Stock History deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
