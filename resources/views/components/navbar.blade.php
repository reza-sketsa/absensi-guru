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
