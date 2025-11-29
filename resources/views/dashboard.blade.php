@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-grid">
        <div class="stat-card">
            <h3>Total Bahan Baku</h3>
            <div class="stat-value">{{ $totalBahanBaku }}</div>
        </div>

        <div class="stat-card">
            <h3>Total Stok Fisik</h3>
            <div class="stat-value">
                {{ number_format($totalStokFisik, 0, ',', '.') }} <span style="font-size: 14px; color: #666;">Unit</span>
            </div>
        </div>

        <div class="stat-card">
            <h3>Total Supplier</h3>
            <div class="stat-value">{{ $totalSupplier }}</div>
        </div>
    </div>

    @if(count($stokHabisLogs) > 0)
        <div style="margin-bottom: 30px;">
            <h3 class="section-title" style="color: var(--error);">⚠️ Peringatan Stok Habis (Terbaru)</h3>
            <div class="table-container" style="border-left: 5px solid var(--error);">
                <table>
                    <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Tanggal Habis</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stokHabisLogs as $log)
                        <tr>
                            <td style="font-weight: bold;">{{ $log->bahanBaku->name ?? 'Item Terhapus' }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->tanggal_habis)->format('d F Y') }}</td>
                            <td style="color: var(--error);">{{ $log->keterangan }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="padding: 10px 20px; text-align: right;">
                    <a href="{{ route('logs.stok-habis') }}" style="color: var(--text-primary); text-decoration: none; font-size: 13px; font-weight: 600;">Lihat Semua Log →</a>
                </div>
            </div>
        </div>
    @endif

    <h3 class="section-title">Aktivitas Stok Terakhir</h3>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Bahan Baku</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Oleh</th>
                <th>Waktu</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentActivities as $history)
                <tr>
                    <td>{{ $history->bahanBaku->name ?? '-' }}</td>
                    <td>
                            <span style="
                                background-color: {{ $history->type->value === 'in' ? '#d4edda' : '#f8d7da' }};
                                color: {{ $history->type->value === 'in' ? '#155724' : '#721c24' }};
                                padding: 4px 8px;
                                border-radius: 4px;
                                font-size: 12px;
                                font-weight: 600;
                                text-transform: uppercase;">
                                {{ $history->type->value }}
                            </span>
                    </td>
                    <td>
                        {{ number_format($history->quantity, 0, ',', '.') }}
                        <span style="font-size: 11px; color: #888;">{{ $history->bahanBaku->satuan->name ?? '' }}</span>
                    </td>
                    <td>{{ $history->creator->name ?? 'Sistem' }}</td>
                    <td>{{ $history->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                        Belum ada aktivitas transaksi.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
