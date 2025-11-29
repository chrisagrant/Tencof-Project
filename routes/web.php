<?php

use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogStokHabisController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /* * MASTER DATA: SATUANS
     * ------------------------------------------------------------------
     * GET    /satuans              -> satuans.index   (Table List)
     * GET    /satuans/create       -> satuans.create  (Form Tambah)
     * POST   /satuans              -> satuans.store   (Proses Simpan)
     * GET    /satuans/{id}/edit    -> satuans.edit    (Form Edit)
     * PUT    /satuans/{id}         -> satuans.update  (Proses Update)
     * DELETE /satuans/{id}         -> satuans.destroy (Proses Hapus)
     */
    Route::resource('satuans', SatuanController::class)->except(['show']);

    /* * MASTER DATA: SUPPLIERS
     * ------------------------------------------------------------------
     * GET    /suppliers            -> suppliers.index   (Table List)
     * GET    /suppliers/create     -> suppliers.create  (Form Tambah)
     * POST   /suppliers            -> suppliers.store   (Proses Simpan)
     * GET    /suppliers/{id}/edit  -> suppliers.edit    (Form Edit)
     * PUT    /suppliers/{id}       -> suppliers.update  (Proses Update)
     * DELETE /suppliers/{id}       -> suppliers.destroy (Proses Hapus)
     */
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    /* * MASTER DATA: BAHAN BAKU (Trigger 'stock' otomatis update, read-only di form)
     * ------------------------------------------------------------------
     * GET    /bahan-bakus            -> bahan-bakus.index   (Table List)
     * GET    /bahan-bakus/create     -> bahan-bakus.create  (Form Tambah)
     * POST   /bahan-bakus            -> bahan-bakus.store   (Proses Simpan)
     * GET    /bahan-bakus/{id}/edit  -> bahan-bakus.edit    (Form Edit)
     * PUT    /bahan-bakus/{id}       -> bahan-bakus.update  (Proses Update)
     * DELETE /bahan-bakus/{id}       -> bahan-bakus.destroy (Proses Hapus)
     */
    Route::resource('bahan-bakus', BahanBakuController::class)->except(['show']);

    /* * TRANSAKSI: STOCKS (Pembelian/Restock via Stored Procedure)
     * ------------------------------------------------------------------
     * GET    /stocks            -> stocks.index   (Table History Pembelian)
     * GET    /stocks/create     -> stocks.create  (Form Restock Barang)
     * POST   /stocks            -> stocks.store   (Proses SP Restock)
     * GET    /stocks/{id}/edit  -> stocks.edit    (Form Edit Invoice)
     * PUT    /stocks/{id}       -> stocks.update  (Proses Update Invoice)
     * DELETE /stocks/{id}       -> stocks.destroy (Hapus Invoice)
     */
    Route::resource('stocks', StockController::class)->except(['show']);

    // Laporan View Database (view_stock_details)
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');

    /* REPORTING & LOGS (Read Only) */
    Route::get('/stock-histories', [StockHistoryController::class, 'index'])->name('stock-histories.index');

    // Log Otomatis Trigger Stok Habis
    Route::get('/logs/stok-habis', [LogStokHabisController::class, 'index'])->name('logs.stok-habis');

    /* TRANSAKSI: PENGELUARAN (Pemakaian Barang - Trigger OUT) */
    Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');

    /* * MASTER DATA: USERS (Khusus Owner)
    * ------------------------------------------------------------------
    * CRUD User untuk menambah Admin/Kasir baru.
    */
    Route::resource('users', UserController::class)->except(['show']);
});

Route::middleware('guest')->group(function () {
    // Authentication
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});


