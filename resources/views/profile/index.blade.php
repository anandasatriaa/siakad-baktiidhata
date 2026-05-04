@extends('layouts.app')

@section('title', 'Profil Saya')
@section('subtitle', 'Kelola informasi akun dan keamanan Anda.')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3">
                            <img src="{{ asset('assets/images/faces/1.jpg') }}" alt="Avatar" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        </div>

                        <h3 class="mt-3">{{ Auth::user()->name }}</h3>
                        <p class="text-small">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Profil</h4>
                </div>
                <div class="card-body">
                    @php
                        $canEditBasic = in_array(Auth::user()->role, ['super_admin', 'admin']);
                    @endphp
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" 
                                class="form-control @error('name') is-invalid @enderror {{ !$canEditBasic ? 'bg-light' : '' }}" 
                                placeholder="Nama Lengkap" value="{{ old('name', Auth::user()->name) }}"
                                {{ !$canEditBasic ? 'readonly' : '' }}>
                            @if(!$canEditBasic)
                                <small class="text-muted">Nama hanya dapat diubah oleh administrator.</small>
                            @endif
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" 
                                class="form-control @error('email') is-invalid @enderror {{ !$canEditBasic ? 'bg-light' : '' }}" 
                                placeholder="Your Email" value="{{ old('email', Auth::user()->email) }}"
                                {{ !$canEditBasic ? 'readonly' : '' }}>
                            @if(!$canEditBasic)
                                <small class="text-muted">Email hanya dapat diubah oleh administrator.</small>
                            @endif
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Ganti Password</h5>
                        <p class="text-muted small">Kosongkan jika tidak ingin mengganti password.</p>

                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Password Saat Ini">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Password Baru">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Konfirmasi Password Baru">
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
