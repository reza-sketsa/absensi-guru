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
        value="hadir"
        checked>

      <label class="btn btn-outline-success" for="hadir{{ $s->id }}">
        Hadir
      </label>


      <input type="radio"
        class="btn-check"
        name="absen[{{ $s->id }}]"
        id="izin{{ $s->id }}"
        value="izin">

      <label class="btn btn-outline-warning" for="izin{{ $s->id }}">
        Izin
      </label>


      <input type="radio"
        class="btn-check"
        name="absen[{{ $s->id }}]"
        id="sakit{{ $s->id }}"
        value="sakit">

      <label class="btn btn-outline-danger" for="sakit{{ $s->id }}">
        Sakit
      </label>

    </div>

  </div>
</div>

@endforeach