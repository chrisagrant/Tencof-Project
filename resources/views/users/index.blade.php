@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            + Tambah User Baru
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Terdaftar Sejak</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td style="font-weight: 500;">
                        {{ $user->name }}
                        @if(Auth::id() === $user->id)
                            <span style="font-size: 11px; color: #28a745; font-weight: bold; margin-left: 5px;">(You)</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                            <span style="
                                padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase;
                                background-color: {{ $user->role->value === 'owner' ? '#000' : ($user->role->value === 'admin' ? '#007bff' : '#6c757d') }};
                                color: #fff;
                            ">
                                {{ $user->role->value }}
                            </span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-small">
                                Edit
                            </a>

                            @if(Auth::id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Hapus user ini? Akses login mereka akan hilang.')">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-small" disabled style="opacity: 0.5; cursor: not-allowed;">Hapus</button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                        Belum ada data user.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
@endsection
