@extends('welcome')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Input Penilaian Baru: {{ $student->nama }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluation.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="penilaian[0][student_id]" value="{{ $student->id }}">

                    <input type="hidden" name="schedule_id" value="1"> <input type="hidden" name="tanggal"
                        value="{{ date('Y-m-d') }}">

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Mata Pelajaran</label>
                            <select name="subject_id" class="form-select" required>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Tugas">Tugas</option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Penilaian</label>
                            <input type="text" name="nama_penilaian" class="form-control"
                                placeholder="Contoh: Ulangan Harian 1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nilai Akhir (0-100)</label>
                        <input type="number" name="penilaian[0][nilai]" class="form-control" min="0" max="100"
                            placeholder="0-100" required>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary px-5">Simpan Penilaian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
