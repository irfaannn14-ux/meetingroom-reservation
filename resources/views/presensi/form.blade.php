@extends('layout.main')
@section('title', 'Form Presensi')

@section('content')
<style>
  .custom-dropdown-container { position: relative; display: block; }
  .custom-dropdown-button {
    background-color: #ffffff; border: 1px solid #ced4da; color: #495057;
    padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5;
    border-radius: 0.25rem; cursor: pointer; text-align: left;
    display: flex; align-items: center; justify-content: space-between; width: 100%;
  }
  .custom-dropdown-content {
    display: none; position: absolute; background-color: #f6f6f6; min-width: 100%;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 100; border: 1px solid #ddd;
    border-radius: 0.25rem; overflow: hidden; max-height: 260px; overflow-y: auto;
  }
  .custom-dropdown-content a { color: black; padding: 12px 16px; text-decoration: none; display: block; }
  .custom-dropdown-content a:hover { background-color: #ddd; }
  .custom-dropdown-input {
    box-sizing: border-box; font-size: 14px; padding: 12px 16px; border: none;
    border-bottom: 1px solid #ced4da; width: 100%; border-radius: 0; outline: none; background-color: #f8f9fa;
  }
  .custom-dropdown-input:focus { background-color: #fff; border-bottom-color: #007bff; }
  .show-dropdown { display: block; }

  /* Signature Pad */
  .signature-pad-wrapper { border: 1px dashed #ced4da; border-radius: .25rem; background: #fff; position: relative; }
  .signature-pad-instruction {
    position: absolute; top: .5rem; right: .75rem; font-size: .825rem; color: #6c757d;
    background: rgba(255,255,255,.8); padding: .125rem .375rem; border-radius: .25rem;
  }
  canvas#signature-pad { width: 100%; height: 240px; display: block; }
</style>

<div class="container mt-5" style="max-width: 600px;">
  <div class="card p-4 shadow-lg border-0">
    <h4 class="mb-4 text-center">Form Presensi</h4>

    {{-- pesan error umum (AJAX) --}}
    <div id="formAlert" class="alert alert-danger d-none" role="alert"></div>

    <form id="formPresensi" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="pengajuan_id" value="{{ $id }}">

      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" required>
      </div>

      {{-- JABATAN: textfield manual --}}
      <div class="mb-3">
        <label class="form-label">Jabatan</label>
        <input type="text" name="jabatan" id="fieldJabatan" class="form-control" placeholder="Tulis jabatan Anda" required>
      </div>

      {{-- ORGANISASI: dropdown + opsi Lainnya -> munculkan textfield --}}
      <div class="mb-3">
        <label class="form-label">Organisasi</label>
        <div class="custom-dropdown-container">
          <div class="custom-dropdown-button form-control" role="button" aria-haspopup="listbox" aria-expanded="false" onclick="toggleOrgDropdown()">
            <span id="selected-org">Pilih organisasi...</span>
            <span aria-hidden="true">&#9660;</span>
          </div>
          <div id="org-dropdown-content" class="custom-dropdown-content" role="listbox">
            <input type="text" class="custom-dropdown-input" onkeyup="filterOrgOptions(event)" placeholder="Cari organisasi...">
            @foreach($organizations as $org)
              @if(!in_array($org->organization_name, ['ADMIN', 'SUPER ADMIN']))
                <a href="#" data-value="{{ $org->bkd_organization_id }}" data-text="{{ $org->organization_name }}">
                  {{ $org->organization_name }}
                </a>
              @endif
            @endforeach
            <a href="#" data-value="lainnya" data-text="Lainnya">Lainnya</a>
          </div>
        </div>
        <input type="hidden" name="organisasi" id="organisasi-input" required>

        {{-- Textfield manual organisasi (muncul jika pilih Lainnya) --}}
        <div id="org-manual-wrapper" class="mt-2 d-none">
          <input type="text" name="organisasi_manual" id="org-manual-input" class="form-control" placeholder="Tulis nama organisasi Anda">
          <div class="form-text">Isi bila memilih <strong>Lainnya</strong>.</div>
        </div>
      </div>

      {{-- TTD DIGITAL: Signature Pad --}}
      <div class="mb-3">
        <label class="form-label">TTD Digital</label>
        <div class="signature-pad-wrapper">
          <span class="signature-pad-instruction">Tanda tangani di sini</span>
          <canvas id="signature-pad"></canvas>
        </div>
        <div class="d-flex gap-2 mt-2">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="sigUndoBtn">Undo</button>
          <button type="button" class="btn btn-outline-danger btn-sm" id="sigClearBtn">Bersihkan</button>
        </div>
        <div class="form-text">Gunakan mouse atau jari (layar sentuh). Ukuran berkas maksimal 2&nbsp;MB.</div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
  </div>
</div>

{{-- Signature Pad library --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
(function () {
  const form = document.getElementById('formPresensi');
  const alertBox = document.getElementById('formAlert');

  const orgInput = document.getElementById('organisasi-input');
  const selectedOrgSpan = document.getElementById('selected-org');
  const orgManualWrapper = document.getElementById('org-manual-wrapper');
  const orgManualInput = document.getElementById('org-manual-input');

  const postUrl = "{{ route('presensi.store') }}";
  const sukmaUrl = 'https://sukma.jatimprov.go.id/fe';

  const LAINNYA_VALUE = 'lainnya';
  const MAX_SIG_BYTES = 2 * 1024 * 1024; // 2 MB

  function showError(msg) {
    alertBox.textContent = msg;
    alertBox.classList.remove('d-none');
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
  function hideError() {
    alertBox.textContent = '';
    alertBox.classList.add('d-none');
  }

  // ====== DROPDOWN ORGANISASI ======
  window.toggleOrgDropdown = function() {
    const dd = document.getElementById("org-dropdown-content");
    const btn = document.querySelector('.custom-dropdown-button');
    const expanded = dd.classList.toggle("show-dropdown");
    btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');

    if (expanded) {
      const searchInput = dd.querySelector('.custom-dropdown-input');
      if (searchInput) {
        searchInput.value = '';
        dd.querySelectorAll('a').forEach(a => a.style.display = '');
        const noMsg = dd.querySelector('.no-results-message');
        if (noMsg) noMsg.style.display = 'none';
      }
    }
  };

  window.filterOrgOptions = function(ev) {
    const input = ev.target;
    const filter = (input.value || '').toUpperCase();
    const div = document.getElementById("org-dropdown-content");
    const links = div.querySelectorAll("a");
    let visibleCount = 0;

    links.forEach(a => {
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

  document.getElementById("org-dropdown-content").addEventListener('click', function(event) {
    const a = event.target;
    if (a.tagName !== 'A') return;
    event.preventDefault();

    const selectedValue = a.getAttribute('data-value');
    const selectedText = a.getAttribute('data-text');

    orgInput.value = selectedValue;
    selectedOrgSpan.textContent = selectedText === 'Lainnya' ? 'Lainnya (isi manual)' : selectedText;
    document.getElementById("org-dropdown-content").classList.remove("show-dropdown");
    document.querySelector('.custom-dropdown-button')?.setAttribute('aria-expanded', 'false');

    // Toggle field manual
    if (selectedValue === LAINNYA_VALUE) {
      orgManualWrapper.classList.remove('d-none');
      orgManualInput.disabled = false;
      orgManualInput.required = true;
      setTimeout(() => orgManualInput.focus(), 60);
    } else {
      orgManualWrapper.classList.add('d-none');
      orgManualInput.disabled = true;
      orgManualInput.required = false;
      orgManualInput.value = '';
    }
  });

  // Tutup dropdown saat klik di luar container
  window.addEventListener('click', function(event) {
    if (!event.target.closest('.custom-dropdown-container')) {
      const dropdown = document.getElementById("org-dropdown-content");
      if (dropdown.classList.contains('show-dropdown')) {
        dropdown.classList.remove('show-dropdown');
        document.querySelector('.custom-dropdown-button')?.setAttribute('aria-expanded', 'false');
      }
    }
  });

  // ====== SIGNATURE PAD ======
  let signaturePad;
  const canvas = document.getElementById('signature-pad');

  function resizeSignatureCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    const { width } = canvas.getBoundingClientRect();
    const heightCss = 240;
    canvas.width = Math.floor(width * ratio);
    canvas.height = Math.floor(heightCss * ratio);
    const ctx = canvas.getContext('2d');
    ctx.scale(ratio, ratio);
    if (signaturePad && !signaturePad.isEmpty()) {
      const data = signaturePad.toData();
      signaturePad.clear();
      signaturePad.fromData(data);
    }
  }

  function initSignaturePad() {
    if (!window.SignaturePad) {
      showError('Gagal memuat komponen TTD Digital. Silakan muat ulang halaman.');
      return;
    }
    signaturePad = new SignaturePad(canvas, { minWidth: 0.5, maxWidth: 2.5, throttle: 16 });
    resizeSignatureCanvas();
    window.addEventListener('resize', () => {
      clearTimeout(window.__sigResizeT);
      window.__sigResizeT = setTimeout(resizeSignatureCanvas, 120);
    });
  }
  initSignaturePad();

  document.getElementById('sigClearBtn').addEventListener('click', () => signaturePad?.clear());
  document.getElementById('sigUndoBtn').addEventListener('click', () => {
    if (!signaturePad) return;
    const data = signaturePad.toData();
    if (data && data.length) { data.pop(); signaturePad.fromData(data); }
  });

  function dataURLToBlob(dataURL) {
    const parts = dataURL.split(';base64,');
    const contentType = parts[0].split(':')[1] || 'image/png';
    const byteString = atob(parts[1]);
    const len = byteString.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) bytes[i] = byteString.charCodeAt(i);
    return new Blob([bytes], { type: contentType });
  }

  // ====== SUBMIT ======
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideError();

    // Validasi organisasi
    if (!orgInput.value) {
      showError('Silakan pilih organisasi.');
      return;
    }
    if (orgInput.value === LAINNYA_VALUE) {
      const orgText = (orgManualInput.value || '').trim();
      if (!orgText) {
        showError('Silakan isi nama organisasi pada kolom yang muncul.');
        return;
      }
    }

    // Validasi tanda tangan
    if (!signaturePad || signaturePad.isEmpty()) {
      showError('Silakan bubuhkan TTD Digital pada kotak yang tersedia.');
      return;
    }

    // Siapkan payload
    const formData = new FormData(form);

    // Lampirkan berkas TTD sebagai PNG (blob)
    const dataURL = signaturePad.toDataURL('image/png');
    const sigBlob = dataURLToBlob(dataURL);

    if (sigBlob.size > MAX_SIG_BYTES) {
      showError('Ukuran TTD melebihi 2 MB. Silakan bersihkan dan tanda tangani kembali.');
      return;
    }
    formData.append('ttd', sigBlob, 'ttd.png');

    try {
      const res = await fetch(postUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          'Accept': 'application/json'
        },
        body: formData
      });

      let data;
      const ct = res.headers.get('content-type') || '';
      if (ct.includes('application/json')) {
        data = await res.json();
      } else {
        const text = await res.text();
        if (res.status === 413 || /payload too large/i.test(text)) {
          throw new Error('Ukuran TTD melebihi 2 MB. Silakan ulangi tanda tangan.');
        }
        throw new Error('Gagal menyimpan presensi. Coba lagi.');
      }

      if (!res.ok || !data.ok) {
        throw new Error(data?.message || 'Gagal menyimpan presensi.');
      }

      // 1) Buka SUKMA di tab baru
      window.open(sukmaUrl, '_blank', 'noopener,noreferrer');

      // 2) Redirect ke History
      window.location.href = data.redirect;

    } catch (err) {
      showError(err.message || 'Terjadi kesalahan.');
    }
  });
})();
</script>

@endsection
