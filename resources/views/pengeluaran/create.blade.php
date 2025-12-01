@extends('layouts.app')

@section('title', 'Input Barang Keluar')

@section('content')
    <div class="table-container" style="padding: 30px; max-width: 600px;">

        <h3 class="section-title">Form Penggunaan / Barang Keluar</h3>

        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong>ℹ️ Info Sistem:</strong>
            Form ini digunakan untuk mencatat pemakaian bahan baku (Barang Keluar).
            Stok akan <strong>berkurang otomatis</strong> (via Trigger Database) setelah disimpan.
        </div>

        @if(session('warning'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Stok Menipis!',
        text: '{{ session("warning") }}',
        confirmButtonColor: '#d33',
    });
</script>
@endif

@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@endif


        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="bahan_baku_id">Pilih Bahan Baku yang Digunakan</label>
                <select name="bahan_baku_id" id="bahan_baku_id" class="@error('bahan_baku_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($bahanBakus as $bb)
                        <option value="{{ $bb->id }}" {{ old('bahan_baku_id') == $bb->id ? 'selected' : '' }}>
                            {{ $bb->name }} (Sisa Stok: {{ number_format($bb->stock, 0, ',', '.') }} {{ $bb->satuan->name }})
                        </option>
                    @endforeach
                </select>
                @error('bahan_baku_id')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="quantity">Jumlah yang Dikeluarkan</label>
                <input
                    type="number"
                    step="0.01"
                    id="quantity"
                    name="quantity"
                    class="@error('quantity') is-invalid @enderror"
                    value="{{ old('quantity') }}"
                    placeholder="Contoh: 5"
                    required
                    min="0.1"
                >
                <small style="color: #666;">Pastikan jumlah tidak melebihi sisa stok yang tersedia.</small>

                @error('quantity')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror

                @if(session('error'))
                    <span style="color: var(--error); font-size: 13px; display:block; margin-top:5px;">
                        {{ session('error') }}
                    </span>
                @endif
            </div>

            <div class="modal-footer" style="background: transparent; padding: 20px 0 0 0; border: none;">
                <a href="{{ route('dashboard') }}" class="btn" style="text-decoration: none; margin-right: 10px;">
                    Batal
                </a>

                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin mengurangi stok barang ini?')">
                    Proses Pengurangan Stok
                </button>
            </div>
        </form>
    </div>
@endsection
