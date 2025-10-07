@extends('layout.main')
@section('title', 'Form Presensi')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
  <div class="card p-4 shadow-lg border-0">
    <h4 class="mb-4 text-center">Form Presensi (ID: {{ $id }})</h4>

    {{-- pesan error umum (AJAX) --}}
    <div id="formAlert" class="alert alert-danger d-none" role="alert"></div>

    <form id="formPresensi" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="pengajuan_id" value="{{ $id }}">

      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Jabatan</label>
        <select name="jabatan" id="fieldJabatan" class="form-select" required>
          <option value="" selected disabled>Pilih jabatan...</option>
          <option value="OPD">OPD</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Organisasi</label>
        <select name="organisasi" id="fieldOrganisasi" class="form-select" required>
          <option value="" selected disabled>Pilih organisasi...</option>
          @foreach($organisasiOptions as $val => $label)
            <option value="{{ $val }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">TTD Digital (PDF saja)</label>
        <input type="file"
               name="ttd"
               id="fieldTtd"
               class="form-control"
               accept="application/pdf"
               required>
        <div class="form-text">Maks 2 MB, format .pdf</div>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Kirim Presensi
      </button>
    </form>
  </div>
</div>

<script>
(function () {
  const form = document.getElementById('formPresensi');
  const alertBox = document.getElementById('formAlert');
  const jabatanEl = document.getElementById('fieldJabatan');
  const orgEl = document.getElementById('fieldOrganisasi');
  const sukmaUrl = 'https://sukma.jatimprov.go.id/fe';
  const postUrl = "{{ route('presensi.store') }}";

  const EKST_VALUE = 'eksternal';
  const EKST_LABEL = 'Eksternal';

  function findEksternalOption() {
    return Array.from(orgEl.options).find(o => o.value === EKST_VALUE) || null;
  }

  function addEksternalOptionIfMissing() {
    if (!findEksternalOption()) {
      const opt = document.createElement('option');
      opt.value = EKST_VALUE;
      opt.textContent = EKST_LABEL;
      orgEl.appendChild(opt);
    }
  }

  function removeEksternalOptionIfExists() {
    const opt = findEksternalOption();
    if (opt) opt.remove();
  }

  function lockOrganisasiIfNeeded() {
    const isLainnya = jabatanEl.value === 'Lainnya';

    if (isLainnya) {
      addEksternalOptionIfMissing();
      orgEl.value = EKST_VALUE;
      orgEl.setAttribute('disabled', 'disabled');
    } else {
      orgEl.removeAttribute('disabled');
      if (orgEl.value === EKST_VALUE) orgEl.value = '';
      removeEksternalOptionIfExists();
    }
  }

  // state awal: tidak ada eksternal
  removeEksternalOptionIfExists();
  lockOrganisasiIfNeeded();

  jabatanEl.addEventListener('change', lockOrganisasiIfNeeded);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    alertBox.classList.add('d-none');
    alertBox.textContent = '';

    const ttdInput = document.getElementById('fieldTtd');
    if (ttdInput.files.length === 0 || ttdInput.files[0].type !== 'application/pdf') {
      alertBox.textContent = 'TTD Digital wajib berupa file PDF.';
      alertBox.classList.remove('d-none');
      return;
    }

    const formData = new FormData(form);

    if (jabatanEl.value === 'Lainnya') {
      formData.set('organisasi', EKST_VALUE);
    }

    try {
      const res = await fetch(postUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
      });

      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data?.message || 'Gagal menyimpan presensi.');

      // 1) buka SUKMA di tab baru
      window.open(sukmaUrl, '_blank');
      // 2) kembali ke history (flash success dari server)
      window.location.href = data.redirect;

    } catch (err) {
      alertBox.textContent = err.message || 'Terjadi kesalahan.';
      alertBox.classList.remove('d-none');
    }
  });
})();
</script>

@endsection
