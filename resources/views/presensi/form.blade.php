@extends('layout.main')
@section('title', 'Form Presensi')

@section('content')
<style>
  .custom-dropdown-container {
    position: relative;
    display: block;
  }
  .custom-dropdown-button {
    background-color: #ffffff;
    border: 1px solid #ced4da;
    color: #495057;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    cursor: pointer;
    text-align: left;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
  }
  .custom-dropdown-content {
    display: none;
    position: absolute;
    background-color: #f6f6f6;
    min-width: 100%;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 100;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    overflow: hidden;
    max-height: 200px;
    overflow-y: auto;
  }
  .custom-dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }
  .custom-dropdown-content a:hover {
    background-color: #ddd;
  }
  .custom-dropdown-input {
    box-sizing: border-box;
    font-size: 14px;
    padding: 12px 16px;
    border: none;
    border-bottom: 1px solid #ced4da;
    width: 100%;
    border-radius: 0;
    outline: none;
    background-color: #f8f9fa;
  }
  .custom-dropdown-input:focus {
    background-color: #fff;
    border-bottom-color: #007bff;
  }
  .show-dropdown { display: block; }
</style>

<div class="container mt-5" style="max-width: 600px;">
  <div class="card p-4 shadow-lg border-0">
    <h4 class="mb-4 text-center">Form Presensi</h4>

    {{-- pesan error umum (AJAX) --}}
    <div id="formAlert" class="alert alert-danger d-none" role="alert"></div>

    <form id="formPresensi"
      method="POST"
      action="{{ route('presensi.store') }}"
      enctype="multipart/form-data">
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
        <div class="custom-dropdown-container">
          <div class="custom-dropdown-button form-control" onclick="toggleOrgDropdown()">
            <span id="selected-org">Pilih organisasi...</span>
            <span>&#9660;</span>
          </div>
          <div id="org-dropdown-content" class="custom-dropdown-content">
            <input type="text" class="custom-dropdown-input" onkeyup="filterOrgOptions()" placeholder="Cari organisasi...">
            @foreach($organizations as $org)
              @if(!in_array($org->organization_name, ['ADMIN', 'SUPER ADMIN']))
                <a href="#" 
                   data-value="{{ $org->bkd_organization_id }}" 
                   data-text="{{ $org->organization_name }}">
                   {{ $org->organization_name }}
                </a>
              @endif
            @endforeach
            {{-- Eksternal HANYA muncul saat Jabatan = Lainnya --}}
            <a href="#" id="org-eksternal" class="d-none" data-value="eksternal" data-text="Eksternal">Eksternal</a>
          </div>
        </div>
        <input type="hidden" name="organisasi" id="organisasi-input" required>
      </div>

      <div class="mb-3">
        <label class="form-label">TTD Digital</label>
        <input type="file"
               name="ttd"
               id="fieldTtd"
               class="form-control"
               accept="application/pdf"
               required>
        <div class="form-text">Maks 2 MB, format .pdf</div>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Submit
      </button>
    </form>
  </div>
</div>

<script>
(function () {
  const form = document.getElementById('formPresensi');
  const alertBox = document.getElementById('formAlert');
  const jabatanEl = document.getElementById('fieldJabatan');
  const orgInput = document.getElementById('organisasi-input');
  const selectedOrgSpan = document.getElementById('selected-org');
  const eksternalLink = document.getElementById('org-eksternal');

  const sukmaUrl = 'https://sukma.jatimprov.go.id/fe';
  const postUrl = "{{ route('presensi.store') }}";

  const EKST_VALUE = 'eksternal';
  const EKST_LABEL = 'Eksternal';
  const MAX_PDF_BYTES = 2 * 1024 * 1024; // 2MB

  function showError(msg) {
    alertBox.textContent = msg;
    alertBox.classList.remove('d-none');
  }
  function hideError() {
    alertBox.textContent = '';
    alertBox.classList.add('d-none');
  }

  // Toggle dropdown
  window.toggleOrgDropdown = function() {
    document.getElementById("org-dropdown-content").classList.toggle("show-dropdown");
  };

  // Filter opsi (hormati item yang disembunyikan dengan class d-none)
  window.filterOrgOptions = function() {
    const input = event.target;
    const filter = (input.value || '').toUpperCase();
    const div = document.getElementById("org-dropdown-content");
    const links = div.querySelectorAll("a");
    let visibleCount = 0;

    links.forEach(a => {
      if (a.classList.contains('d-none')) { a.style.display = 'none'; return; }
      const txtValue = (a.getAttribute('data-text') || a.textContent || '').toUpperCase();
      const show = txtValue.indexOf(filter) > -1;
      a.style.display = show ? '' : 'none';
      if (show) visibleCount++;
    });

    let noResultsMsg = div.querySelector('.no-results-message');
    if (visibleCount === 0 && filter.length > 0) {
      if (!noResultsMsg) {
        noResultsMsg = document.createElement('div');
        noResultsMsg.className = 'no-results-message';
        noResultsMsg.style.padding = '12px 16px';
        noResultsMsg.style.color = '#6c757d';
        noResultsMsg.style.fontStyle = 'italic';
        noResultsMsg.textContent = 'Tidak ada organisasi yang ditemukan';
        div.appendChild(noResultsMsg);
      }
      noResultsMsg.style.display = 'block';
    } else if (noResultsMsg) {
      noResultsMsg.style.display = 'none';
    }
  };

  // Pilih organisasi
  document.getElementById("org-dropdown-content").addEventListener('click', function(event) {
    if (event.target.tagName === 'A' && !event.target.classList.contains('d-none')) {
      event.preventDefault();
      const selectedValue = event.target.getAttribute('data-value');
      const selectedText = event.target.getAttribute('data-text');
      orgInput.value = selectedValue;
      selectedOrgSpan.textContent = selectedText;
      document.getElementById("org-dropdown-content").classList.remove("show-dropdown");
    }
  });

  // Tutup dropdown saat klik di luar container
  window.addEventListener('click', function(event) {
    if (!event.target.closest('.custom-dropdown-container')) {
      const dropdown = document.getElementById("org-dropdown-content");
      if (dropdown.classList.contains('show-dropdown')) dropdown.classList.remove('show-dropdown');
    }
  });

  // Clear filter saat dropdown dibuka
  document.querySelector('.custom-dropdown-button').addEventListener('click', function() {
    const searchInput = document.querySelector('#org-dropdown-content .custom-dropdown-input');
    if (searchInput) {
      searchInput.value = '';
      const links = document.querySelectorAll('#org-dropdown-content a');
      links.forEach(link => {
        if (!link.classList.contains('d-none')) link.style.display = '';
      });
    }
  });

  // Tampilkan/hilangkan “Eksternal” sesuai jabatan + kunci dropdown saat Lainnya
  function lockOrganisasiIfNeeded() {
    const isLainnya = jabatanEl.value === 'Lainnya';
    const ddBtn = document.querySelector('.custom-dropdown-button');

    if (isLainnya) {
      if (eksternalLink) eksternalLink.classList.remove('d-none'); // tampilkan "Eksternal"
      orgInput.value = EKST_VALUE;
      selectedOrgSpan.textContent = EKST_LABEL;
      ddBtn.style.pointerEvents = 'none';
      ddBtn.style.opacity = '0.6';
      // pastikan dropdown tertutup
      document.getElementById("org-dropdown-content").classList.remove("show-dropdown");
    } else {
      if (eksternalLink) eksternalLink.classList.add('d-none');   // sembunyikan "Eksternal"
      if (orgInput.value === EKST_VALUE) {
        orgInput.value = '';
        selectedOrgSpan.textContent = 'Pilih organisasi...';
      }
      ddBtn.style.pointerEvents = 'auto';
      ddBtn.style.opacity = '1';
    }
  }

  // Initial state
  lockOrganisasiIfNeeded();
  jabatanEl.addEventListener('change', lockOrganisasiIfNeeded);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideError();

    const ttdInput = document.getElementById('fieldTtd');
    const file = ttdInput.files[0];

    if (!file) {
      showError('Silakan unggah TTD Digital (PDF).');
      return;
    }
    if (file.type !== 'application/pdf') {
      showError('TTD Digital wajib berupa file PDF.');
      return;
    }
    if (file.size > MAX_PDF_BYTES) {
      showError('Ukuran file TTD melebihi 2 MB. Silakan kompres atau pilih file lain.');
      return;
    }

    const formData = new FormData(form);
    // Jika Lainnya, paksa organisasi=eksternal (karena dropdown dikunci)
    if (jabatanEl.value === 'Lainnya') {
      formData.set('organisasi', EKST_VALUE);
    }

    try {
      const res = await fetch(postUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          'Accept': 'application/json' // minta JSON untuk 422 error
        },
        body: formData
      });

      // Toleransi jika server balas HTML (mis. 413 dari web server/PHP)
      let data;
      const ct = res.headers.get('content-type') || '';
      if (ct.includes('application/json')) {
        data = await res.json();
      } else {
        const text = await res.text();
        if (res.status === 413 || /payload too large/i.test(text)) {
          throw new Error('Ukuran file TTD melebihi 2 MB. Silakan kompres atau pilih file lain.');
        }
        throw new Error('Gagal menyimpan presensi. Coba lagi.');
      }

      if (!res.ok || !data.ok) {
        throw new Error(data?.message || 'Gagal menyimpan presensi.');
      }

      // 1) HANYA SUKMA yang buka tab baru
      window.open('https://sukma.jatimprov.go.id/fe', '_blank', 'noopener,noreferrer');

      // 2) Tab utama kembali ke History (di tab yang sama) dengan flash dari server
      window.location.href = data.redirect;

    } catch (err) {
      showError(err.message || 'Terjadi kesalahan.');
    }
  });
})();
</script>

@endsection
