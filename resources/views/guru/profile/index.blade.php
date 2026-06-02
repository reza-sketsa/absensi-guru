@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="container py-4 pb-5 mb-4">

        {{-- Header --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                        style="width:56px;height:56px;font-size:22px;">
                        {{ strtoupper(substr($teacher->nama_guru, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">{{ $teacher->nama_guru }}</h5>
                        <small class="text-white opacity-75">{{ $user->username }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Diri --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-person-vcard me-2 text-primary"></i>Data Diri
                </h6>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block">NIP</small>
                        <span class="fw-semibold">{{ $teacher->nip ?? '-' }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <span class="fw-semibold">{{ $teacher->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Agama</small>
                        <span class="fw-semibold">{{ $teacher->agama ?? '-' }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Tanggal Lahir</small>
                        <span class="fw-semibold">
                            {{ $teacher->tgl_lahir ? \Carbon\Carbon::parse($teacher->tgl_lahir)->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">No. Telepon</small>
                        <span class="fw-semibold">{{ $teacher->no_telp ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Alamat</small>
                        <span class="fw-semibold">{{ $teacher->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-lock me-2 text-primary"></i>Ganti Password
                </h6>
            </div>
            <div class="card-body px-4 py-3">
                @if ($errors->any())
                    <div class="alert alert-danger small">{{ $errors->first() }}</div>
                @endif
                <form action="{{ route('guru.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Password</button>
                </form>
            </div>
        </div>

    </div>
@endsection
