@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Notifikasi Stok Minimum</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bahan</th>
                <th>Stok Sekarang</th>
                <th>Satuan ID</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
            <tr>
                <td>{{ $log->nama_bahan }}</td>
                <td>{{ $log->stok }}</td>
                <td>{{ $log->satuan_id }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data stok minimum</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
