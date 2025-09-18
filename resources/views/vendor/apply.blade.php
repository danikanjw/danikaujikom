@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Pengajuan Menjadi Vendor</h3>
                </div>
                <div class="card-body">
                    <p>Untuk menjadi vendor di Toko Alat Kesehatan, Anda perlu mendaftar terlebih dahulu dan menunggu persetujuan admin.</p>
                    <div class="mt-4">
                        <a href="{{ route('vendor.register') }}" class="btn btn-primary me-2">Daftar sebagai Vendor</a>
                        <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
