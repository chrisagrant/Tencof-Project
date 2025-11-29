@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            + Tambah Supplier
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Supplier</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($suppliers as $index => $supplier)
                <tr>
                    <td>{{ $suppliers->firstItem() + $index }}</td>
                    <td style="font-weight: 500;">{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone }}</td>

                    <td title="{{ $supplier->address }}">
                        {{ Str::limit($supplier->address, 50) }}
                    </td>

                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-small">
                                Edit
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Hapus supplier ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                        Belum ada data supplier.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $suppliers->links() }}
    </div>
@endsection
