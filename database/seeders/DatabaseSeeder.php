<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\Satuan;
use App\Models\StockHistory;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // MEMBUAT USER
        $owner = User::create([
            'name' => 'Owner Ten Coffee',
            'email' => 'owner@tencoffee.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        $admin = User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@tencoffee.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@tencoffee.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        // MEMBUAT MASTER DATA (Satuan & Supplier)
        $kg = Satuan::create(['name' => 'Kilogram (kg)']);
        $gr = Satuan::create(['name' => 'Gram (gr)']);
        $pcs = Satuan::create(['name' => 'Pcs']);
        $ltr = Satuan::create(['name' => 'Liter (L)']);

        $suppA = Supplier::create([
            'name' => 'PT. Kopi Nusantara',
            'phone' => '08123456789',
            'address' => 'Jl. Kebun Kopi No. 1, Aceh'
        ]);

        $suppB = Supplier::create([
            'name' => 'CV. Susu Segar',
            'phone' => '08987654321',
            'address' => 'Jl. Peternakan No. 99, Lembang'
        ]);

        // MEMBUAT BAHAN BAKU (Awalnya Stok 0)
        $kopi = BahanBaku::create([
            'name' => 'Biji Kopi Arabica',
            'satuan_id' => $kg->id,
            'created_by' => $owner->id
        ]);

        $susu = BahanBaku::create([
            'name' => 'Susu UHT Full Cream',
            'satuan_id' => $ltr->id,
            'created_by' => $owner->id
        ]);

        $gula = BahanBaku::create([
            'name' => 'Gula Aren Cair',
            'satuan_id' => $ltr->id,
            'created_by' => $owner->id
        ]);

        // ============================================================
        // PENGUJIAN LOGIC DATABASE (Stored Procedure & Trigger)
        // ============================================================

        // SKENARIO A: RESTOCK BARANG (Test Stored Procedure)
        // Kita beli 100 KG Kopi.
        // Harapan: Tabel Stocks terisi, History terisi (IN), Bahan Baku stok jadi 100.
        DB::statement("CALL sp_restock_item(?, ?, ?, ?, ?)", [
            $kopi->id,
            $suppA->id,
            100,
            150000,
            $admin->id
        ]);

        // Kita beli 50 Liter Susu
        DB::statement("CALL sp_restock_item(?, ?, ?, ?, ?)", [
            $susu->id,
            $suppB->id,
            50,
            18000,
            $admin->id
        ]);

        // SKENARIO B: PENGGUNAAN BARANG (Test Trigger UPDATE Stock - OUT)
        // Kita pakai 5 KG Kopi.
        // Harapan: Trigger trg_update_stock_otomatis jalan, stok Kopi di tabel bahan_bakus sisa 95.
        StockHistory::create([
            'bahan_baku_id' => $kopi->id,
            'type' => 'out', // Menggunakan string langsung atau Enum
            'quantity' => 5,
            'created_by' => $admin->id
        ]);

        // SKENARIO C: STOK HABIS (Test Trigger Log Stok Habis)
        // Kita restock Gula dulu 10 Liter
        DB::statement("CALL sp_restock_item(?, ?, ?, ?, ?)", [
            $gula->id, $suppB->id, 10, 25000, $admin->id
        ]);
        // Lalu kita habiskan Gula 10 Liter sekaligus
        StockHistory::create([
            'bahan_baku_id' => $gula->id,
            'type' => 'out',
            'quantity' => 10, // Stok jadi 0
            'created_by' => $admin->id
        ]);
    }
}
