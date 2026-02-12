@extends('welcome')

@section('title', 'Dashboard Admin')

@section('content')

<div class="container">

    <h3 class="mb-4">Dashboard Absensi Guru</h3>

    <!-- CARD STATISTIK -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Total Guru</h6>
                    <h3>{{ $totalGuru ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Hadir Hari Ini</h6>
                    <h3>{{ $hadir ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Terlambat</h6>
                    <h3>{{ $terlambat ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Tidak Hadir</h6>
                    <h3>{{ $alpha ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- JAM -->
    <div class="card mb-4 shadow">
        <div class="card-body text-center">

            <h4 id="jam"></h4>
            <p>{{ date('l, d M Y') }}</p>

        </div>
    </div>


    <!-- TABEL ABSENSI -->
    <div class="card shadow">

        <div class="card-header">
            <h5>Absensi Hari Ini</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

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
                            <td colspan="4" class="text-center">
                                Belum ada data absensi hari ini
                            </td>
                        </tr>

                    @endif

                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- SCRIPT JAM -->
<script>
function updateJam() {
    const now = new Date();
    const jam = now.toLocaleTimeString();
    document.getElementById("jam").innerHTML = jam;
}

setInterval(updateJam, 1000);
updateJam();
</script>

@endsection