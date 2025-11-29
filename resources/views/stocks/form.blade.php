@extends('layouts.app')

@section('title', isset($stock) ? 'Edit Data Pembelian' : 'Input Restock Barang')

@section('content')
    <div class="table-container" style="padding: 30px; max-width: 700px;">

        <h3 class="section-title">
            {{ isset($stock) ? 'Edit Data Invoice Pembelian' : 'Form Restock Barang (Barang Masuk)' }}
        </h3>

        @if(isset($stock))
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeeba;">
                <strong>⚠️ Perhatian:</strong>
                Anda sedang dalam mode Edit. Perubahan Quantity di sini <strong>TIDAK AKAN</strong> mengubah jumlah stok fisik di gudang secara otomatis (karena Trigger hanya jalan saat Insert baru). Gunakan fitur ini hanya untuk memperbaiki kesalahan catat administrasi.
            </div>
        @else
            <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <strong>ℹ️ Info Sistem:</strong>
                Data yang Anda input akan diproses oleh <strong>Stored Procedure</strong> database. Stok barang akan bertambah otomatis dan riwayat akan tercatat.
            </div>
        @endif

        <form action="{{ isset($stock) ? route('stocks.update', $stock->id) : route('stocks.store') }}" method="POST">
            @csrf
            @if(isset($stock))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="bahan_baku_id">Bahan Baku</label>
                <select name="bahan_baku_id" id="bahan_baku_id" class="@error('bahan_baku_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Barang yang Dibeli --</option>
                    @foreach($bahanBakus as $bb)
                        <option
                            value="{{ $bb->id }}"
                            {{ (old('bahan_baku_id') == $bb->id || (isset($stock) && $stock->bahan_baku_id == $bb->id)) ? 'selected' : '' }}
                        >
                            {{ $bb->name }} (Satuan: {{ $bb->satuan->name }})
                        </option>
                    @endforeach
                </select>
                @error('bahan_baku_id') <span style="color: var(--error);">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="@error('supplier_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supp)
                        <option
                            value="{{ $supp->id }}"
                            {{ (old('supplier_id') == $supp->id || (isset($stock) && $stock->supplier_id == $supp->id)) ? 'selected' : '' }}
                        >
                            {{ $supp->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id') <span style="color: var(--error);">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="quantity">Jumlah Barang Masuk (Qty)</label>
                    <input
                        type="number"
                        step="0.01"
                        id="quantity"
                        name="quantity"
                        class="@error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', $stock->quantity ?? '') }}"
                        placeholder="0"
                        required
                    >
                    @error('quantity') <span style="color: var(--error);">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="unit_price">Harga Beli Satuan (Rp)</label>
                    <input
                        type="number"
                        id="unit_price"
                        name="unit_price"
                        class="@error('unit_price') is-invalid @enderror"
                        value="{{ old('unit_price', $stock->unit_price ?? '') }}"
                        placeholder="Contoh: 15000"
                        required
                    >
                    <small style="color: #666;">Harga per satuan (bukan total)</small>
                    @error('unit_price') <span style="color: var(--error);">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="modal-footer" style="background: transparent; padding: 20px 0 0 0; border: none;">
                <a href="{{ route('stocks.index') }}" class="btn" style="text-decoration: none; margin-right: 10px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($stock) ? 'Update Invoice' : 'Proses Restock' }}
                </button>
            </div>
        </form>
    </div>
@endsection
