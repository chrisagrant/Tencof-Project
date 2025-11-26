<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stocks = Stock::with(['bahanBaku', 'supplier'])->get();
            return response()->json(['data' => $stocks], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        try {
            $stock = Stock::create([
                'bahan_baku_id' => $request->bahan_baku_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'supplier_id' => $request->supplier_id,
                'created_by' => auth()->id()
            ]);
            return response()->json(['message' => 'Stock created successfully', 'data' => $stock], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        try {
            $stock->load(['bahanBaku', 'supplier']);
            return response()->json(['data' => $stock], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        try {
            $stock->update($request->only(['bahan_baku_id', 'quantity', 'unit_price', 'supplier_id']));
            return response()->json(['message' => 'Stock updated successfully', 'data' => $stock], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        try {
            $stock->delete();
            return response()->json(['message' => 'Stock deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
