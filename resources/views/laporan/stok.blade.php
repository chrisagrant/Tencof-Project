@extends('layouts.app')

@section('title', 'Laporan Stok (View)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 class="section-title">Laporan Stok Barang (Real-time)</h3>
            <p style="color: #666; font-size: 14px; margin-top: -15px;">
                Data diambil langsung dari Database View: <code>view_stock_details</code>
            </p>
        </div>

        <button onclick="window.print()" class="btn" style="background: #fff; border: 1px solid #ccc; color: #333; display: flex; align-items: center; gap: 8px;">
            <span>🖨️</span> Cetak / PDF
        </button>
    </div>

    <div class="technical-info" style="background-color: #e2e3e5; color: #383d41; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #d6d8db; font-size: 13px;">
        <strong>ℹ️ Info Teknis:</strong>
        Halaman ini membuktikan implementasi <strong>SQL View</strong>.
        Aplikasi tidak melakukan <em>Join Table</em> manual di codingan PHP, melainkan memanggil Virtual Table yang sudah disiapkan di MySQL.
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Stok Saat Ini</th>
                <th>Terakhir Diupdate</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($laporan as $index => $row)
                <tr>
                    <td>{{ $laporan->firstItem() + $index }}</td>

                    <td style="font-weight: 500;">{{ $row->nama_barang }}</td>

                    <td>{{ $row->satuan }}</td>

                    <td style="font-weight: bold; font-size: 16px;">
                        {{ number_format($row->stok_saat_ini, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($row->terakhir_diupdate)->translatedFormat('d F Y, H:i') }}
                    </td>

                    <td>
                        @if($row->stok_saat_ini <= 0)
                            <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold;">
                                    HABIS
                                </span>
                        @elseif($row->stok_saat_ini < 10)
                            <span style="background-color: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold;">
                                    MENIPIS
                                </span>
                        @else
                            <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold;">
                                    AMAN
                                </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                        Tidak ada data stok ditemukan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $laporan->links() }}
    </div>

    <style>
        @media print {
            .sidebar, .header, .btn, .technical-info, .pagination {
                display: none !important;
            }
            .main-content {
                margin: 0;
                padding: 0;
            }
            .table-container {
                box-shadow: none;
                border: 1px solid #000;
            }
            body {
                background: white;
            }
        }
    </style>
@endsection
