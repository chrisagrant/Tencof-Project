@extends('layouts.app')

@section('title', isset($satuan) ? 'Edit Satuan' : 'Tambah Satuan')

@section('content')
    <div class="table-container" style="padding: 30px; max-width: 600px;">

        <h3 class="section-title">
            {{ isset($satuan) ? 'Edit Data Satuan' : 'Tambah Satuan Baru' }}
        </h3>

        <form action="{{ isset($satuan) ? route('satuans.update', $satuan->id) : route('satuans.store') }}" method="POST">
            @csrf

            @if(isset($satuan))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Nama Satuan</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="@error('name') is-invalid @enderror"
                    placeholder="Contoh: Kilogram, Liter, Pcs"
                    value="{{ old('name', $satuan->name ?? '') }}"
                    required
                >

                @error('name')
                <span style="color: var(--error); font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-footer" style="background: transparent; padding: 20px 0 0 0; border: none;">
                <a href="{{ route('satuans.index') }}" class="btn" style="text-decoration: none; margin-right: 10px;">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    {{ isset($satuan) ? 'Update Perubahan' : 'Simpan Data' }}
                </button>
            </div>
        </form>
    </div>
@endsection
