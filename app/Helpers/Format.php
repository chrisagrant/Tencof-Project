<?php

namespace App\Helpers;

use Carbon\Carbon;

class Format
{
    public static function rupiah($angka)
    {
        return "Rp " . number_format($angka, 0, ',', '.');
    }

    public static function tanggal($tanggal)
    {
        return Carbon::parse($tanggal)->translatedFormat('d F Y H:i');
    }

    public static function tanggalSingkat($tanggal)
    {
        return Carbon::parse($tanggal)->format('d/m/Y');
    }
}
