<header class="p-3 shadow-sm bg-white sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('img/logo-smpn1.png') }}" alt="logo" width="50" height="50" class="me-2">
            </a>
            <h1 class="h5 mb-0">SISKUL <small class="text-muted fw-light">| {{ Auth::user()->role ?? '' }}</small></h1>
        </div>

        <div class="auth-links">
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle border-0" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <h6 class="dropdown-header">Menu Profil</h6>
                        </li>

                        @if (Auth::user()->role == 'Admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.tahun-ajaran.index') }}">
                                    <i class="bi bi-calendar-check me-2 text-primary"></i>Tahun Akademik
                                </a>
                            </li>
                            <hr class="dropdown-divider">
                        @endif

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3">Login</a>
            @endauth
        </div>
    </div>

    {{-- Bottom Navbar --}}
    @auth
        <nav class="navbar navbar-expand fixed-bottom bg-white shadow-lg border-top">
            <ul class="navbar-nav mx-auto w-100 justify-content-around">

                {{-- MENU KHUSUS ADMIN --}}
                @if (Auth::user()->role == 'Admin')
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Dashboard</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('admin/guru*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('admin.guru.index') }}">
                            <i class="bi bi-person-badge fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Guru</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('admin/mapel*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('admin.mapel.index') }}">
                            <i class="bi bi-book fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Mapel</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('admin/kelas*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('admin.kelas.index') }}">
                            <i class="bi bi-door-open fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Kelas</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('admin/jadwal*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('admin.jadwal.index') }}">
                            <i class="bi bi-calendar3 fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Jadwal</small>
                        </a>
                    </li>

                    {{-- MENU KHUSUS GURU --}}
                @elseif (Auth::user()->role == 'Guru')
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Dashboard</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('guru/absensi*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('guru.absensi') }}">
                            <i class="bi bi-calendar-check fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Absensi</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('guru/penilaian*') || request()->is('guru/evaluations*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('guru.penilaian.index') }}">
                            <i class="bi bi-journal-bookmark fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Penilaian</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('guru/kelas*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('guru.kelas.index') }}">
                            <i class="bi bi-people fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Siswa</small>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    @endauth
</header>
