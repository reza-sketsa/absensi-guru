@extends('welcome')

@section('title','Home')

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

<div class="card app-card shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-2">Data Siswa</h5>
    <p class="mb-0 app-muted">halaman data</p>
  </div>
</div>
@endsection