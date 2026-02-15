@extends('welcome')

@section('title','Absen')

@section('content')
<div class="mb-3">
  <h4 class="fw-bold">Absensi Siswa</h4>
  <p class="text-muted mb-0">Absen hari ini</p>
</div>

<div class="card app-card shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Pilih Kehadiran</h5>

    <button class="btn btn-success w-100 mb-2">
      Hadir
    </button>

    <button class="btn btn-warning w-100 mb-2 text-white">
      Izin
    </button>

    <button class="btn btn-danger w-100">
      Sakit
    </button>
  </div>
</div>
@endsection