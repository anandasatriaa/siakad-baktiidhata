@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Halaman Utama SIAKAD SMK Bakti Idhata')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Selamat Datang!</h4>
        </div>
        <div class="card-body">
            Halo <strong>{{ Auth::user()->name }}</strong>. Anda login sebagai <strong>{{ strtoupper(str_replace('_', ' ', Auth::user()->role)) }}</strong>.
        </div>
    </div>
</section>
@endsection
