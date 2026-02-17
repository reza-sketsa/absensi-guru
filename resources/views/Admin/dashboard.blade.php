@extends('welcome')

@section('title','Dashboard Admin')

@section('content')
<div class="container">

  <h3 class="mb-4">Dashboard Absensi Guru</h3>

  <!-- CARD STATISTIK (RESPONSIVE) -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card app-card shadow-sm text-center">
        <div class="card-body">
          <h6>Total Guru</h6>
          <h3 class="fw-bold">{{ $totalGuru ?? 0 }}</h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card app-card shadow-sm text-center">
        <div class="card-body">
          <h6>Hadir</h6>
          <h3 class="fw-bold">{{ $hadir ?? 0 }}</h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card app-card shadow-sm text-center">
        <div class="card-body">
          <h6>Terlambat</h6>
          <h3 class="fw-bold">{{ $terlambat ?? 0 }}</h3>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card app-card shadow-sm text-center">
        <div class="card-body">
          <h6>Alpha</h6>
          <h3 class="fw-bold">{{ $alpha ?? 0 }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- JAM -->
  <div class="card shadow-sm mb-4">
    <div class="card-body text-center">
      <h4 id="jam"></h4>
      <p class="mb-0">{{ date('l, d M Y') }}</p>
    </div>
  </div>

  <!-- TABEL ABSENSI -->
  <div class="card shadow-sm">
    <div class="card-header">
      <h5 class="mb-0">Absensi Hari Ini</h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jam Masuk</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($absensi) && count($absensi) > 0)
            @foreach($absensi as $a)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $a->nama }}</td>
              <td>{{ $a->jam_masuk }}</td>
              <td>{{ $a->status }}</td>
            </tr>
            @endforeach
          @else
            <tr>
              <td colspan="4" class="text-center">Belum ada data absensi</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  function updateJam(){
    const now = new Date()
    document.getElementById('jam').innerHTML = now.toLocaleTimeString()
  }
  setInterval(updateJam,1000)
  updateJam()
</script>
@endpush