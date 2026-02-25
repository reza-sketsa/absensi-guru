@extends('welcome')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Absensi Siswa</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf

                @foreach($siswa as $s)

                <div class="card app-card shadow-sm mb-3">
                    <div class="card-body">

                        <h6 class="fw-bold mb-3">
                            {{ $s->nama }}
                        </h6>

                        <div class="btn-group w-100" role="group">

                            <input type="radio"
                                class="btn-check"
                                name="absen[{{ $s->id }}]"
                                id="hadir{{ $s->id }}"
                                value="Hadir"
                                checked>

                            <label class="btn btn-outline-success"
                                for="hadir{{ $s->id }}">
                                Hadir
                            </label>


                            <input type="radio"
                                class="btn-check"
                                name="absen[{{ $s->id }}]"
                                id="izin{{ $s->id }}"
                                value="Izin">

                            <label class="btn btn-outline-warning"
                                for="izin{{ $s->id }}">
                                Izin
                            </label>


                            <input type="radio"
                                class="btn-check"
                                name="absen[{{ $s->id }}]"
                                id="sakit{{ $s->id }}"
                                value="Sakit">

                            <label class="btn btn-outline-danger"
                                for="sakit{{ $s->id }}">
                                Sakit
                            </label>


                            <input type="radio"
                                class="btn-check"
                                name="absen[{{ $s->id }}]"
                                id="alpa{{ $s->id }}"
                                value="Alpa">

                            <label class="btn btn-outline-dark"
                                for="alpa{{ $s->id }}">
                                Alpa
                            </label>

                        </div>

                    </div>
                </div>

                @endforeach

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        Simpan Absensi
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection