<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_stok_habis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->onDelete('cascade');
            $table->date('tanggal_habis');
            $table->string('keterangan');
            $table->timestamps();
        });

        DB::unprepared("DROP VIEW IF EXISTS view_stock_details");
        $viewSql = "
            CREATE VIEW view_stock_details AS
            SELECT
                bb.id AS bahan_baku_id,
                bb.name AS nama_barang,
                s.name AS satuan,
                bb.stock AS stok_saat_ini,
                bb.updated_at AS terakhir_diupdate
            FROM bahan_bakus bb
            JOIN satuans s ON bb.satuan_id = s.id;
        ";
        DB::unprepared($viewSql);

        DB::unprepared("DROP TRIGGER IF EXISTS trg_update_stock_otomatis");
        $triggerUpdateStock = "
            CREATE TRIGGER trg_update_stock_otomatis
            AFTER INSERT ON stock_histories
            FOR EACH ROW
            BEGIN
                -- Jika tipe 'in' (Pembelian), tambah stok
                IF NEW.type = 'in' THEN
                    UPDATE bahan_bakus
                    SET stock = stock + NEW.quantity
                    WHERE id = NEW.bahan_baku_id;

                -- Jika tipe 'out' (Pengeluaran), kurangi stok
                ELSEIF NEW.type = 'out' THEN
                    UPDATE bahan_bakus
                    SET stock = stock - NEW.quantity
                    WHERE id = NEW.bahan_baku_id;
                END IF;
            END
        ";
        DB::unprepared($triggerUpdateStock);

        DB::unprepared("DROP TRIGGER IF EXISTS trg_log_barang_habis");
        $triggerLogHabis = "
            CREATE TRIGGER trg_log_barang_habis
            AFTER UPDATE ON bahan_bakus
            FOR EACH ROW
            BEGIN
                -- Cek jika stok jadi 0 (atau kurang) padahal sebelumnya ada (>0)
                IF NEW.stock <= 0 AND OLD.stock > 0 THEN
                    INSERT INTO log_stok_habis (bahan_baku_id, tanggal_habis, keterangan, created_at, updated_at)
                    VALUES (NEW.id, CURDATE(), 'Stok barang habis', NOW(), NOW());
                END IF;
            END
        ";
        DB::unprepared($triggerLogHabis);

        // Procedure ini membungkus 2 insert (ke table stock & history) menjadi 1 perintah
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_restock_item");
        $procSql = "
            CREATE PROCEDURE sp_restock_item(
                IN p_bahan_baku_id BIGINT,
                IN p_supplier_id BIGINT,
                IN p_quantity DECIMAL(10,2),
                IN p_unit_price DECIMAL(10,2),
                IN p_user_id BIGINT
            )
            BEGIN
                -- Masuk ke tabel Stocks (Data Pembelian)
                INSERT INTO stocks (bahan_baku_id, supplier_id, quantity, unit_price, created_by, created_at, updated_at)
                VALUES (p_bahan_baku_id, p_supplier_id, p_quantity, p_unit_price, p_user_id, NOW(), NOW());

                -- Masuk ke stock_histories (Ini akan memicu Trigger trg_update_stock_otomatis)
                INSERT INTO stock_histories (bahan_baku_id, type, quantity, created_by, created_at, updated_at)
                VALUES (p_bahan_baku_id, 'in', p_quantity, p_user_id, NOW(), NOW());
            END
        ";
        DB::unprepared($procSql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_restock_item");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_log_stock_movement");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_update_stock_otomatis");
        DB::unprepared("DROP VIEW IF EXISTS view_stock_details");
        Schema::dropIfExists('log_stok_habis');
    }
};
