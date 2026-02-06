<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Absensi Guru')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <header class="p-3 shadow-sm">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('img/logo-smpn1.png') }}" alt="logo" width="70" height="70" class="me-3">
                </a>
                <h1 class="h4 mb-0">Absensi Guru</h1>
            </div>

            <nav class="navbar navbar-expand">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/absensi">Absensi</a></li>
                    <li class="nav-item"><a class="nav-link" href="/data">Data</a></li>
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                </ul>
            </nav>

            <div class="auth-links">
                @guest
                    <a href="/login" class="btn btn-outline-primary">Login</a>
                @else
                    <form action="/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <main class="container mt-4">
        @yield('content')
    </main>
</body>
</html>
