@extends('welcome')

@section('title','Data')

@section('content')
<div class="mb-3">
  <h4 class="fw-bold">Data Siswa</h4>
  <p class="text-muted mb-0">Informasi siswa dan nilai</p>
</div>

<div class="card app-card shadow-sm mb-3">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Profil</h5>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">Nama</li>
      <li class="list-group-item">NIS / NIP</li>
      <li class="list-group-item">Kelas / Jabatan</li>
    </ul>
  </div>
</div>

<div class="card app-card shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Nilai</h5>
    <p class="text-muted mb-0">Belum ada data nilai</p>
  </div>
</div>
@endsection