@extends('layouts.app')

@section('title', 'Data Satuan')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('satuans.create') }}" class="btn btn-primary">
            + Tambah Satuan
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th>Nama Satuan</th>
                <th style="width: 200px;">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($satuans as $index => $satuan)
                <tr>
                    <td style="text-align: center;">
                        {{ $satuans->firstItem() + $index }}
                    </td>
                    <td>{{ $satuan->name }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('satuans.edit', $satuan->id) }}" class="btn btn-small">
                                Edit
                            </a>

                            <form action="{{ route('satuans.destroy', $satuan->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Yakin ingin menghapus satuan ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 40px; color: #999;">
                        Belum ada data satuan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $satuans->links() }}
    </div>
@endsection
