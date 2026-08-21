@extends('layouts.app')

@section('title', 'Manajemen Hak Akses')
@section('subtitle', 'Kelola akun Admin, Guru Piket, dan Kepala Sekolah')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Akun Pengguna</h4>
            @if (Auth::user()->role !== 'kepala_sekolah')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Tambah Akun
            </a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            @if (Auth::user()->role !== 'kepala_sekolah')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'super_admin')
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($user->role == 'admin')
                                    <span class="badge bg-primary">Admin</span>
                                @elseif($user->role == 'guru_piket')
                                    <span class="badge bg-warning">Guru Piket</span>
                                @elseif($user->role == 'kepala_sekolah')
                                    <span class="badge bg-info">Kepala Sekolah</span>
                                @endif
                            </td>
                            @if (Auth::user()->role !== 'kepala_sekolah')
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id != auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
