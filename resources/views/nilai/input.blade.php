@extends('welcome')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Input Penilaian Baru: {{ $student->nama }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Mata Pelajaran</label>
                        <select name="subject_id" class="form-select" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Guru Pengampu</label>
                        <select name="teacher_id" class="form-select" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->nama_guru }}</option>
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

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Nama Penilaian</label>
                        <input type="text" name="nama_penilaian" class="form-control" placeholder="Contoh: Ulangan Harian 1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nilai Akhir</label>
                    <input type="number" name="nilai" class="form-control" min="0" max="100" placeholder="0-100" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Simpan Penilaian</button>
            </form>
        </div>
    </div>
</div>
@endsection
