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
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('satuan_id')
                ->references('id')
                ->on('satuans')
                ->restrictOnDelete();
            $table->decimal('stock', 10, 2)->default(0);
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
        Schema::dropIfExists('bahan_bakus');
    }
};
