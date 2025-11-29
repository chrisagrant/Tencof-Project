@extends('layouts.app')

@section('title', 'Data Stok Masuk (Pembelian)')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('stocks.create') }}" class="btn btn-primary">
            + Input Pembelian Stok (Restock)
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Bahan Baku</th>
                <th>Supplier</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Oleh</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $stock)
                <tr>
                    <td>{{ $stock->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-weight: 500;">{{ $stock->bahanBaku->name ?? '-' }}</td>
                    <td>{{ $stock->supplier->name ?? '-' }}</td>

                    <td style="font-weight: bold;">
                        {{ number_format($stock->quantity, 0, ',', '.') }}
                    </td>

                    <td>
                        Rp {{ number_format($stock->unit_price, 0, ',', '.') }}
                    </td>

                    <td style="color: var(--success); font-weight: 500;">
                        Rp {{ number_format($stock->quantity * $stock->unit_price, 0, ',', '.') }}
                    </td>

                    <td>{{ $stock->creator->name ?? 'System' }}</td>

                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-small">
                                Edit
                            </a>
                            <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Hapus data pembelian ini? (Hanya menghapus arsip, stok fisik tidak berubah otomatis)')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        Belum ada data pembelian stok.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $stocks->links() }}
    </div>
@endsection
