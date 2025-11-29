<?php

namespace App\Models;

use App\Enum\StockTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    /** @use HasFactory<\Database\Factories\StockHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'bahan_baku_id',
        'type',
        'quantity',
        'created_by'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'type' => StockTypeEnum::class,
    ];

    /**
     * Get the bahan baku that this history belongs to
     */
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    /**
     * Get the user who created this stock history
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
