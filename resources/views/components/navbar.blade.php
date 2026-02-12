  <header class="p-3 shadow-sm">
      <div class="container d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
              <a href="/" class="navbar-brand">
                  <img src="{{ asset('img/logo-smpn1.png') }}" alt="logo" width="70" height="70" class="me-3">
              </a>
              <h1 class="h4 mb-0">Absensi Guru</h1>
          </div>

          <nav class="navbar navbar-expand fixed-bottom bg-light">
              <ul class="navbar-nav mx-auto">
                  <li class="nav-item">
                      <a class="nav-link" href="/"><i class="bi bi-house-door"></i></a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="/absensi"><i class="bi bi-calendar-check"></i></a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="/data"><i class="bi bi-person"></i></a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2"></i></a>
                  </li>
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
