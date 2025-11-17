<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    /** @use HasFactory<\Database\Factories\BahanBakuFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'satuan_id',
        'created_by'
    ];

    protected $table = 'bahan_bakus';

    /**
     * Get the satuan that this bahan baku belongs to
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    /**
     * Get the user who created this bahan baku
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the stocks for this bahan baku
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get the stock histories for this bahan baku
     */
    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
