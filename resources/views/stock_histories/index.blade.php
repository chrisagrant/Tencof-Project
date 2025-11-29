@extends('layouts.app')

@section('title', 'Riwayat Transaksi Stok')

@section('content')
    <h3 class="section-title">Riwayat Keluar Masuk Barang</h3>

    <div style="background-color: #fff; padding: 20px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 25px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('stock-histories.index') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">

            <div style="flex: 1; min-width: 200px;">
                <label for="bahan_baku_id" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Filter Bahan Baku</label>
                <select name="bahan_baku_id" id="bahan_baku_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Semua Barang --</option>
                    @foreach($bahanBakus as $bb)
                        <option value="{{ $bb->id }}" {{ request('bahan_baku_id') == $bb->id ? 'selected' : '' }}>
                            {{ $bb->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label for="type" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Filter Tipe</label>
                <select name="type" id="type" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Semua Tipe --</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>IN (Masuk/Pembelian)</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>OUT (Keluar/Dipakai)</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="height: 42px; display: flex; align-items: center; gap: 5px;">
                    <span>🔍</span> Terapkan Filter
                </button>
            </div>

            @if(request()->has('bahan_baku_id') || request()->has('type'))
                <div>
                    <a href="{{ route('stock-histories.index') }}" class="btn" style="height: 42px; display: flex; align-items: center; text-decoration: none; color: #666;">
                        Reset
                    </a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Waktu Transaksi</th>
                <th>Bahan Baku</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Dibuat Oleh</th>
            </tr>
            </thead>
            <tbody>
            @forelse($histories as $history)
                <tr>
                    <td title="{{ $history->created_at->format('d F Y H:i:s') }}">
                        {{ $history->created_at->translatedFormat('d F Y, H:i') }}
                    </td>

                    <td style="font-weight: 500;">{{ $history->bahanBaku->name ?? 'Item Terhapus' }}</td>

                    <td>
                        @if($history->type->value === 'in')
                            <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid #c3e6cb;">
                                    ⬇️ MASUK
                                </span>
                        @else
                            <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid #f5c6cb;">
                                    ⬆️ KELUAR
                                </span>
                        @endif
                    </td>

                    <td style="font-weight: bold;">
                        {{ number_format($history->quantity, 0, ',', '.') }}
                        <span style="font-size: 12px; font-weight: normal; color: #666;">
                                {{ $history->bahanBaku->satuan->name ?? '' }}
                            </span>
                    </td>

                    <td>{{ $history->creator->name ?? 'Sistem' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 50px; color: #999;">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">📭</span>
                        Tidak ada data riwayat transaksi yang sesuai filter.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $histories->withQueryString()->links() }}
    </div>
@endsection
