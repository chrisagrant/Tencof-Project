<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    /** @use HasFactory<\Database\Factories\SatuanFactory> */
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Get the bahan bakus that belong to this satuan
     */
    public function bahanBakus()
    {
        return $this->hasMany(BahanBaku::class);
    }
}
