<?php

namespace App\Http\Controllers;

use App\Http\Requests\Satuan\StoreSatuanRequest;
use App\Http\Requests\Satuan\UpdateSatuanRequest;
use App\Models\Satuan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $satuans = Satuan::latest()->paginate(10);
        return view('satuans.index', [
            'satuans' => $satuans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('satuans.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSatuanRequest $request)
    {
        Satuan::create($request->validated());

        return redirect()->route('satuans.index')->with('success', 'Satuan berhasil ditambahkan.');
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
    public function edit(Satuan $satuan)
    {
        return view('satuans.form', [
            'satuan' => $satuan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSatuanRequest $request, Satuan $satuan)
    {
        $satuan->update($request->validated());

        return redirect()->route('satuans.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Satuan $satuan)
    {
        try {
            $satuan->delete();
            return redirect()->route('satuans.index')->with('success', 'Satuan berhasil dihapus.');
        } catch (QueryException $e) {
            // Menangkap error Foreign Key (RestrictOnDelete)
            if ($e->getCode() == "23000") {
                return back()->with('error', 'Gagal menghapus! Satuan ini sedang digunakan oleh Bahan Baku.');
            }
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
