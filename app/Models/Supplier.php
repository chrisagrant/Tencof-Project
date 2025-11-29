<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address'
    ];

    /**
     * Get the stocks that belong to this supplier
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'supplier_id');
    }
}
