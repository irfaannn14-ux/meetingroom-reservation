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
  .show-dropdown {
    display: block;
  }
</style>

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
            <a href="#" data-value="eksternal" data-text="Eksternal">Eksternal</a>
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
  const orgInput = document.getElementById('organisasi-input');
  const selectedOrgSpan = document.getElementById('selected-org');
  const sukmaUrl = 'https://sukma.jatimprov.go.id/fe';
  const postUrl = "{{ route('presensi.store') }}";

  const EKST_VALUE = 'eksternal';
  const EKST_LABEL = 'Eksternal';

  // Make functions global for onclick handlers (must be before event listeners)
  window.toggleOrgDropdown = function() {
    document.getElementById("org-dropdown-content").classList.toggle("show-dropdown");
  };

  window.filterOrgOptions = function() {
    const input = event.target;
    const filter = input.value.toUpperCase();
    const div = document.getElementById("org-dropdown-content");
    const a = div.getElementsByTagName("a");
    let visibleCount = 0;
    
    for (let i = 0; i < a.length; i++) {
      const txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        a[i].style.display = "";
        visibleCount++;
      } else {
        a[i].style.display = "none";
      }
    }
    
    // Show/hide "no results" message
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

  // Handle organization selection
  document.getElementById("org-dropdown-content").addEventListener('click', function(event) {
    if (event.target.tagName === 'A') {
      event.preventDefault();
      
      const selectedValue = event.target.getAttribute('data-value');
      const selectedText = event.target.getAttribute('data-text');
      
      orgInput.value = selectedValue;
      selectedOrgSpan.textContent = selectedText;
      
      document.getElementById("org-dropdown-content").classList.remove("show-dropdown");
    }
  });

  // Close dropdown when clicking outside
  window.addEventListener('click', function(event) {
    if (!event.target.matches('.custom-dropdown-button') && 
        !event.target.matches('.custom-dropdown-input') && 
        !event.target.closest('.custom-dropdown-button')) {
      const dropdown = document.getElementById("org-dropdown-content");
      if (dropdown.classList.contains('show-dropdown')) {
        dropdown.classList.remove('show-dropdown');
      }
    }
  });

  // Clear search input when dropdown is opened
  document.querySelector('.custom-dropdown-button').addEventListener('click', function() {
    const searchInput = document.querySelector('#org-dropdown-content .custom-dropdown-input');
    if (searchInput) {
      searchInput.value = '';
      // Reset all options to visible
      const links = document.querySelectorAll('#org-dropdown-content a');
      links.forEach(link => link.style.display = '');
    }
  });

  function lockOrganisasiIfNeeded() {
    const isLainnya = jabatanEl.value === 'Lainnya';

    if (isLainnya) {
      orgInput.value = EKST_VALUE;
      selectedOrgSpan.textContent = EKST_LABEL;
      document.querySelector('.custom-dropdown-button').style.pointerEvents = 'none';
      document.querySelector('.custom-dropdown-button').style.opacity = '0.6';
    } else {
      document.querySelector('.custom-dropdown-button').style.pointerEvents = 'auto';
      document.querySelector('.custom-dropdown-button').style.opacity = '1';
      if (orgInput.value === EKST_VALUE) {
        orgInput.value = '';
        selectedOrgSpan.textContent = 'Pilih organisasi...';
      }
    }
  }

  // Initial state
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
