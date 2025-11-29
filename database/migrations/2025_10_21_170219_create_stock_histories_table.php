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
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')
                ->references('id')
                ->on('bahan_bakus')
                ->restrictOnDelete();
            $table->string('type');
            $table->decimal('quantity', 8, 2);
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
        Schema::dropIfExists('stock_histories');
    }
};
