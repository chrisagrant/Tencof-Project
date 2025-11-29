<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')
                ->references('id')
                ->on('bahan_bakus')
                ->restrictOnDelete();
            $table->decimal('quantity', 8, 2);
            $table->decimal('unit_price', 8, 2);
            $table->foreignId('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete();
            $table->foreignId('created_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
