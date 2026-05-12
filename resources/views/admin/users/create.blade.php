@extends('layouts.app')

@section('title', 'Tambah Akun')
@section('subtitle', 'Buat akun pengguna baru untuk administrator atau staf')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Akun</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="role">Hak Akses</label>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-light-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
