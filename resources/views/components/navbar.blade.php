<header class="p-3 shadow-sm bg-white sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('img/logo-smpn1.png') }}" alt="logo" width="50" height="50" class="me-2">
            </a>
            <h1 class="h5 mb-0">Absensi Guru</h1>
        </div>

        <div class="auth-links">
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('students.data') }}">Data Siswa</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
            @endauth
        </div>
    </div>

    {{-- Bottom Navbar --}}
    @auth
        <nav class="navbar navbar-expand fixed-bottom bg-white shadow-lg border-top">
            <ul class="navbar-nav mx-auto w-100 justify-content-around">

                {{-- 1. DASHBOARD (Statistik) --}}
                <li class="nav-item text-center">
                    <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'text-primary' : 'text-muted' }}"
                        href="{{ route('guru.dashboard') }}">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                        <small class="d-block" style="font-size: 10px;">Dashboard</small>
                    </a>
                </li>

                @if (Auth::user()->role == 'Guru')
                    {{-- 2. ABSENSI (List Jadwal) --}}
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('guru/absensi*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('guru.absensi') }}">
                            <i class="bi bi-calendar-check fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Absensi</small>
                        </a>
                    </li>

                    {{-- 3. PENILAIAN (List Kelas) --}}
                    <li class="nav-item text-center">
                        <a class="nav-link {{ request()->is('guru/penilaian*') ? 'text-primary' : 'text-muted' }}"
                            href="{{ route('guru.penilaian.index') }}">
                            <i class="bi bi-journal-bookmark fs-4"></i>
                            <small class="d-block" style="font-size: 10px;">Penilaian</small>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <a href="{{ route('guru.kelas.index') }}" class="nav-link text-center">
                            <i class="bi bi-people"></i>
                            <span class="small d-block">Siswa</span>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    @endauth
</header>
