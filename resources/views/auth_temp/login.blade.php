@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card border-0 shadow-lg w-100" style="max-width: 400px;">
            <div class="card-body p-4 p-md-5">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <div class="bg-gradient-header text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-box-arrow-in-right fs-3"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Selamat Datang</h4>
                    <p class="text-muted small mb-0">Silakan login untuk melanjutkan</p>
                </div>

                {{-- Alert error - custom modern --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert"
                        style="border-left: 4px solid #dc2626; background-color: #fef2f2;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                            <div>
                                <strong class="small">Gagal login:</strong>
                                <div class="small mt-1">{{ $errors->first() }}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="username"
                                class="form-control border-start-0 @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
                        </div>
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" id="passwordField"
                                class="form-control border-start-0 @error('password') is-invalid @enderror"
                                placeholder="Masukkan password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.scripts')
@endpush
