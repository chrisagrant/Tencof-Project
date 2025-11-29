@extends('layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Tambah User Baru')

@section('content')
    <div class="table-container" style="padding: 30px; max-width: 600px;">

        <h3 class="section-title">
            {{ isset($user) ? 'Edit Data Pengguna' : 'Registrasi Pengguna Baru' }}
        </h3>

        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="@error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name ?? '') }}"
                    placeholder="Masukkan nama lengkap"
                    required
                >
                @error('name') <span style="color: var(--error); font-size: 13px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="@error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email ?? '') }}"
                    placeholder="user@tencoffee.com"
                    required
                >
                @error('email') <span style="color: var(--error); font-size: 13px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="role">Role / Jabatan</label>
                <select name="role" id="role" class="@error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $roleEnum)
                        <option
                            value="{{ $roleEnum->value }}"
                            {{ (old('role') == $roleEnum->value || (isset($user) && $user->role->value == $roleEnum->value)) ? 'selected' : '' }}
                        >
                            {{ ucfirst($roleEnum->value) }}
                        </option>
                    @endforeach
                </select>
                @error('role') <span style="color: var(--error); font-size: 13px;">{{ $message }}</span> @enderror
            </div>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

            <div class="form-group">
                <label for="password">
                    Password
                    @if(isset($user)) <span style="font-weight: normal; color: #888; font-size: 12px;">(Kosongkan jika tidak ingin mengubah)</span> @endif
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="@error('password') is-invalid @enderror"
                    placeholder="Minimal 8 karakter"
                    {{ isset($user) ? '' : 'required' }}
                >
                @error('password') <span style="color: var(--error); font-size: 13px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    {{ isset($user) ? '' : 'required' }}
                >
            </div>

            <div class="modal-footer" style="background: transparent; padding: 20px 0 0 0; border: none;">
                <a href="{{ route('users.index') }}" class="btn" style="text-decoration: none; margin-right: 10px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($user) ? 'Update User' : 'Buat User' }}
                </button>
            </div>
        </form>
    </div>
@endsection
