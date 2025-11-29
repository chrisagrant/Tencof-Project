@extends('layouts.app')

@section('title', isset($bahanBaku) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')

@section('content')
    <div class="table-container" style="padding: 30px; max-width: 600px;">

        <h3 class="section-title">
            {{ isset($bahanBaku) ? 'Edit Data Bahan Baku' : 'Input Bahan Baku Baru' }}
        </h3>

        <form action="{{ isset($bahanBaku) ? route('bahan-bakus.update', $bahanBaku->id) : route('bahan-bakus.store') }}" method="POST">
            @csrf
            @if(isset($bahanBaku))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Nama Bahan Baku</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="@error('name') is-invalid @enderror"
                    value="{{ old('name', $bahanBaku->name ?? '') }}"
                    placeholder="Contoh: Biji Kopi Arabica"
                    required
                >
                @error('name')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="satuan_id">Satuan Pengukuran</label>
                <select name="satuan_id" id="satuan_id" class="@error('satuan_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Satuan --</option>
                    @foreach($satuans as $satuan)
                        <option
                            value="{{ $satuan->id }}"
                            {{-- Logic Selected: Jika ID satuan sama dengan (old input ATAU data database), maka selected --}}
                            {{ (old('satuan_id') == $satuan->id || (isset($bahanBaku) && $bahanBaku->satuan_id == $satuan->id)) ? 'selected' : '' }}
                        >
                            {{ $satuan->name }}
                        </option>
                    @endforeach
                </select>
                @error('satuan_id')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            @if(isset($bahanBaku))
                <div class="form-group" style="background: #f9f9f9; padding: 10px; border-radius: 8px; border: 1px dashed #ccc;">
                    <label style="color: #666;">Stok Saat Ini (Tidak bisa diedit disini)</label>
                    <div style="font-size: 18px; font-weight: bold; color: #333;">
                        {{ number_format($bahanBaku->stock, 0, ',', '.') }}
                        {{ $bahanBaku->satuan->name ?? '' }}
                    </div>
                    <small style="color: #999;">* Gunakan menu Stock / Barang Keluar untuk mengubah jumlah.</small>
                </div>
            @endif

            <div class="modal-footer" style="background: transparent; padding: 20px 0 0 0; border: none;">
                <a href="{{ route('bahan-bakus.index') }}" class="btn" style="text-decoration: none; margin-right: 10px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($bahanBaku) ? 'Update Data' : 'Simpan Data' }}
                </button>
            </div>
        </form>
    </div>
@endsection
