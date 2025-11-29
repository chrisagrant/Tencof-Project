@extends('layouts.app')

@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')

@section('content')
    <div class="table-container" style="padding: 30px; max-width: 600px;">

        <h3 class="section-title">
            {{ isset($supplier) ? 'Edit Data Supplier' : 'Registrasi Supplier Baru' }}
        </h3>

        <form action="{{ isset($supplier) ? route('suppliers.update', $supplier->id) : route('suppliers.store') }}" method="POST">
            @csrf
            @if(isset($supplier))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Nama Perusahaan / Supplier</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="@error('name') is-invalid @enderror"
                    value="{{ old('name', $supplier->name ?? '') }}"
                    placeholder="Contoh: PT. Kopi Nusantara"
                    required
                >
                @error('name')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    class="@error('phone') is-invalid @enderror"
                    value="{{ old('phone', $supplier->phone ?? '') }}"
                    placeholder="Contoh: 08123456789"
                    required
                >
                @error('phone')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Alamat Lengkap</label>
                <textarea
                    id="address"
                    name="address"
                    class="@error('address') is-invalid @enderror"
                    rows="3"
                    placeholder="Masukkan alamat lengkap supplier..."
                    required
                >{{ old('address', $supplier->address ?? '') }}</textarea>
                @error('address')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-footer" style="background: transparent; padding: 20px 0 0 0; border: none;">
                <a href="{{ route('suppliers.index') }}" class="btn" style="text-decoration: none; margin-right: 10px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($supplier) ? 'Update Supplier' : 'Simpan Supplier' }}
                </button>
            </div>
        </form>
    </div>
@endsection
