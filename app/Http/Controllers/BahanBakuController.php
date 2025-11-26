<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Http\Requests\StoreBahanBakuRequest;
use App\Http\Requests\UpdateBahanBakuRequest;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $bahanBakus = BahanBaku::with('satuan')->get();
            return response()->json(['data' => $bahanBakus], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBahanBakuRequest $request)
    {
        try {
            $bahanBaku = BahanBaku::create([
                'name' => $request->name,
                'satuan_id' => $request->satuan_id,
                'created_by' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Bahan Baku created successfully', 'data' => $bahanBaku], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanBaku $bahanBaku)
    {
        try {
            $bahanBaku->load('satuan');
            return response()->json(['data' => $bahanBaku], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBahanBakuRequest $request, BahanBaku $bahanBaku)
    {
        try {
            $bahanBaku->update($request->only(['name', 'satuan_id']));
            
            return response()->json(['message' => 'Bahan Baku updated successfully', 'data' => $bahanBaku], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanBaku $bahanBaku)
    {
        try {
            $bahanBaku->delete();
            
            return response()->json(['message' => 'Bahan Baku deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
