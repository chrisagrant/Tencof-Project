<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\SupplierController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Public Authentication routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Dashboard/Main page
Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard')->middleware('auth');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// API Routes for resource management
Route::prefix('api')->group(function () {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Bahan Baku routes
        Route::apiResource('bahan-baku', BahanBakuController::class);
        
        // Satuan routes
        Route::apiResource('satuan', SatuanController::class);
        
        // Supplier routes
        Route::apiResource('supplier', SupplierController::class);
        
        // Stock routes
        Route::apiResource('stock', StockController::class);
        
        // Stock History routes
        Route::apiResource('stock-history', StockHistoryController::class);
    });
});
