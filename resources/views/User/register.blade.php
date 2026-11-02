@extends('layouts.user')

@section('css')
<style>
    body {
        background: #104F17;
    }

    .btn-purple {
        background: #104F17;
        width: 100%;
        color: #fff;
    }
</style>
@endsection

@section('title', 'Halaman Daftar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <h2 class="text-center text-white mb-0 mt-5">PEWA</h2>
            <p class="text-center text-white mb-5">Pengaduan Mahasiswa</p>
            <div class="card mt-5">
                <div class="card-body">
                    <h2 class="text-center mb-5">FORM DAFTAR</h2>
                    <form action="{{ route('pewa.register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="number" name="nim" id="nim" placeholder="NIM" class="form-control">
                            @error('nim')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="form-control" value="{{ old('nama') }}">
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" name="username" id="username" placeholder="Username" class="form-control" value="{{ old('username') }}">
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="number" name="telp" id="telp" placeholder="No. Telp" class="form-control" value="{{ old('telp') }}">
                            @error('telp')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-purple">DAFTAR</button>
                    </form>
                </div>
            </div>
            @if (Session::has('pesan'))
            <div class="alert alert-danger mt-2">
                {{ Session::get('pesan') }}
            </div>
            @endif
            <a href="{{ route('pewa.index') }}" class="btn btn-warning text-white mt-3" style="width: 100%">Kembali ke Halaman Utama</a>
        </div>
    </div>
</div>
@endsection
