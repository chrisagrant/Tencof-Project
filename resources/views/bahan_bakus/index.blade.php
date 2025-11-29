@extends('layouts.app')

@section('title', 'Data Bahan Baku')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('bahan-bakus.create') }}" class="btn btn-primary">
            + Tambah Bahan Baku
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan Baku</th>
                <th>Satuan</th>
                <th>Stok Saat Ini</th> <th>Dibuat Oleh</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bahanBakus as $index => $item)
                <tr>
                    <td>{{ $bahanBakus->firstItem() + $index }}</td>
                    <td style="font-weight: 500;">{{ $item->name }}</td>

                    <td>{{ $item->satuan->name ?? '-' }}</td>

                    <td style="font-weight: bold; color: {{ $item->stock <= 0 ? 'var(--error)' : 'var(--success)' }}">
                        {{ number_format($item->stock, 0, ',', '.') }}
                    </td>

                    <td>{{ $item->creator->name ?? 'System' }}</td>

                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('bahan-bakus.edit', $item->id) }}" class="btn btn-small">
                                Edit
                            </a>
                            <form action="{{ route('bahan-bakus.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Hapus bahan baku ini? History stok juga akan hilang!')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                        Belum ada data bahan baku.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $bahanBakus->links() }}
    </div>
@endsection
