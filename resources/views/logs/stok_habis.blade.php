@extends('layouts.app')

@section('title', 'Log Stok Habis')

@section('content')
    <h3 class="section-title" style="color: var(--error);">⚠️ Log Peringatan Stok Habis</h3>

    <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 5px solid #ffc107; color: #856404;">
        <strong>Tentang Halaman Ini:</strong>
        Data di bawah ini dicatat <strong>secara otomatis oleh Database Trigger</strong> (<code>trg_log_barang_habis</code>)
        setiap kali ada transaksi yang menyebabkan stok bahan baku mencapai angka 0.
        Data ini tidak bisa dimanipulasi manual.
    </div>

    <div class="table-container" style="border-top: 3px solid var(--error);">
        <table>
            <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Waktu Kejadian</th>
                <th>Bahan Baku</th>
                <th>Tanggal Habis (Sistem)</th>
                <th>Keterangan Trigger</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $index => $log)
                <tr style="background-color: #fff5f5;">
                    <td>{{ $logs->firstItem() + $index }}</td>

                    <td>{{ $log->created_at->translatedFormat('d F Y H:i:s') }}</td>

                    <td style="font-weight: bold; color: var(--error);">
                        {{ $log->bahanBaku->name ?? 'Item Terhapus' }}
                    </td>

                    <td>{{ \Carbon\Carbon::parse($log->tanggal_habis)->translatedFormat('l, d F Y') }}</td>

                    <td style="font-style: italic; color: #555;">
                        "{{ $log->keterangan }}"
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 50px; color: #28a745;">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">✅</span>
                        <strong>Aman!</strong> Belum ada log stok habis yang tercatat.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $logs->links() }}
    </div>
@endsection
