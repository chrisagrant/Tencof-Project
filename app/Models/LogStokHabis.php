<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStokHabis extends Model
{
    protected $table = 'log_stok_habis';

    protected $fillable = [
        'bahan_baku_id',
        'tanggal_habis',
        'keterangan',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
