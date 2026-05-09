    @extends('layouts.app')

    @section('title', 'Tambah Guru')

    @section('content')
        <div class="container pb-5">
            <div class="card border-0 shadow-sm bg-primary text-white mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-1">Tambah Guru Baru</h4>
                    <p class="mb-0 opacity-75 small">Isi data guru dan kredensial akun</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.guru.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-0">
                                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-vcard me-2"></i>Informasi
                                    Pribadi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">NIP</label>
                                        <input type="text" name="nip"
                                            class="form-control @error('nip') is-invalid @enderror"
                                            value="{{ old('nip') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nama Lengkap</label>
                                        <input type="text" name="nama_guru"
                                            class="form-control @error('nama_guru') is-invalid @enderror"
                                            value="{{ old('nama_guru') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Jenis Kelamin</label>
                                        <select name="jk" class="form-select" required>
                                            <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Agama</label>
                                        <select name="agama" class="form-select">
                                            @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $ag)
                                                <option value="{{ $ag }}"
                                                    {{ old('agama') == $ag ? 'selected' : '' }}>
                                                    {{ $ag }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control"
                                            value="{{ old('tgl_lahir') }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Nomor Telepon</label>
                                        <input type="text" name="no_telp" class="form-control"
                                            value="{{ old('no_telp') }}" placeholder="08..." required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
                                    </div>
                                    <input type="hidden" name="school_id" value="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-0">
                                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-shield-lock me-2"></i>Kredensial Akun
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Username</label>
                                    <input type="text" id="username" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        placeholder="Contoh: guru_ipa atau pake NIP" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordField" class="form-control"
                                            placeholder="Kosongkan jika tidak diubah">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary shadow">
                                <i class="bi bi-check-lg me-1"></i> Simpan Data Guru
                            </button>
                            <a href="{{ route('admin.guru.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        @include('components.scripts')
    @endpush
