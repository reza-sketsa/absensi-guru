@extends('welcome')

@section('title', 'Home')

@section('content')

    <div class="mb-3">
        <h4 class="app-title fw-bold">Halo, Guru 👋</h4>
        <p class="app-muted">Selamat datang di aplikasi absensi sekolah</p>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-6">
            <a href="/absen" class="text-decoration-none text-dark">
                <div class="card app-card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check fs-1 text-success"></i>
                        <p class="mt-2 mb-0 fw-semibold">Absen</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6">
            <a href="/absensi" class="text-decoration-none text-dark">
                <div class="card app-card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-data fs-1 text-primary"></i>
                        <p class="mt-2 mb-0 fw-semibold">Absensi</p>
                    </div>
                </div>
            </a>
        </div>

    </div>


    <div class="card app-card shadow-sm mb-5">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Data Siswa</h5>

            @if (isset($students) && count($students) > 0)

                @foreach ($students as $s)
                    <div class="border-bottom py-2">
                        <b>{{ $s->nama }}</b><br>
                        <small>NIS: {{ $s->nis }}</small>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Data siswa kosong jir 😹</p>

            @endif

        </div>
    </div>

@endsection
