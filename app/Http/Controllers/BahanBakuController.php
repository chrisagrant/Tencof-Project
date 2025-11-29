<?php

namespace App\Http\Controllers;

use App\Http\Requests\BahanBaku\StoreBahanBakuRequest;
use App\Http\Requests\BahanBaku\UpdateBahanBakuRequest;
use App\Models\BahanBaku;
use App\Models\Satuan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanBakus = BahanBaku::with(['satuan', 'creator'])->latest()->paginate(10);

        return view('bahan_bakus.index', [
            'bahanBakus' => $bahanBakus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satuans = Satuan::all();

        return view('bahan_bakus.form', [
            'satuans' => $satuans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBahanBakuRequest $request)
    {
        BahanBaku::create([
            'name' => $request->name,
            'satuan_id' => $request->satuan_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('bahan-bakus.index')->with('success', 'Bahan baku berhasil ditambahkan.');
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
    public function edit(BahanBaku $bahanBaku)
    {
        $satuans = Satuan::all();

        return view('bahan_bakus.form', [
            'bahanBaku' => $bahanBaku,
            'satuans' => $satuans
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBahanBakuRequest $request, BahanBaku $bahanBaku)
    {
        $bahanBaku->update([
            'name' => $request->name,
            'satuan_id' => $request->satuan_id,
        ]);

        return redirect()->route('bahan-bakus.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanBaku $bahanBaku)
    {
        try {
            $bahanBaku->delete();
            return redirect()->route('bahan-bakus.index')->with('success', 'Bahan baku berhasil dihapus.');
        } catch (QueryException $e) {
            // Error 23000 biasanya Foreign Key Constraint Violation
            if ($e->getCode() == "23000") {
                return back()->with('error', 'Gagal menghapus! Bahan baku ini sudah memiliki riwayat transaksi stok.');
            }
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
