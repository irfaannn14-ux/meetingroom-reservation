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
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 100;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            overflow: hidden;
            max-height: 260px;
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

        /* Signature Pad */
        .signature-pad-wrapper {
            border: 1px dashed #ced4da;
            border-radius: .25rem;
            background: #fff;
            position: relative;
        }

        .signature-pad-instruction {
            position: absolute;
            top: .5rem;
            right: .75rem;
            font-size: .825rem;
            color: #6c757d;
            background: rgba(255, 255, 255, .8);
            padding: .125rem .375rem;
            border-radius: .25rem;
        }

        canvas#signature-pad {
            width: 100%;
            height: 240px;
            display: block;
        }
    </style>

    <div class="container mt-5" style="max-width: 600px;">
        <div class="card p-4 shadow-lg border-0">
            <h4 class="mb-4 text-center">Form Presensi</h4>

            {{-- pesan error umum (AJAX) --}}
            <div id="formAlert" class="alert alert-danger d-none" role="alert"></div>

            <form id="formPresensi" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pengajuan_id" value="{{ $id }}">
                <input type="hidden" name="ttd_path" id="ttd_path">


                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                {{-- JABATAN: textfield manual --}}
                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" id="fieldJabatan" class="form-control"
                        placeholder="Tulis jabatan Anda" required>
                </div>

                {{-- ORGANISASI: dropdown + opsi Lainnya -> munculkan textfield --}}
                <div class="mb-3">
                    <label class="form-label">Organisasi</label>
                    <div class="custom-dropdown-container">
                        <div id="org-dropdown-btn" class="custom-dropdown-button form-control" role="button"
                            aria-haspopup="listbox" aria-expanded="false">
                            <span id="selected-org">Pilih organisasi...</span>
                            <span aria-hidden="true">&#9660;</span>
                        </div>

                        <div id="org-dropdown-content" class="custom-dropdown-content" role="listbox">
                            <input type="text" class="custom-dropdown-input" onkeyup="filterOrgOptions(event)"
                                placeholder="Cari organisasi..." autocomplete="off">
                            @foreach ($organizations as $org)
                                @if (!in_array($org->organization_name, ['ADMIN', 'SUPER ADMIN']))
                                    <a href="#" data-value="{{ $org->bkd_organization_id }}"
                                        data-text="{{ $org->organization_name }}">
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
                        <input type="text" name="organisasi_manual" id="org-manual-input" class="form-control"
                            placeholder="Tulis nama organisasi Anda">
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

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        (function() {
            const form = document.getElementById('formPresensi');
            const alertBox = document.getElementById('formAlert');

            const orgBtn = document.getElementById('org-dropdown-btn'); // <-- pastikan id ini ada di HTML tombol
            const orgList = document.getElementById('org-dropdown-content'); // <-- dan id ini di container list
            const orgInput = document.getElementById('organisasi-input');
            const selectedOrgSpan = document.getElementById('selected-org');
            const orgManualWrap = document.getElementById('org-manual-wrapper');
            const orgManualInput = document.getElementById('org-manual-input');

            const postUrl = "{{ route('presensi.store') }}";
            const sukmaUrl = 'https://sukma.jatimprov.go.id/fe/survey?idUser=2676';

            function showError(msg) {
                alertBox.textContent = msg;
                alertBox.classList.remove('d-none');
            }

            function hideError() {
                alertBox.textContent = '';
                alertBox.classList.add('d-none');
            }

            // ========== DROPDOWN ==========
            if (orgBtn) {
                orgBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const expanded = orgList.classList.toggle('show-dropdown');
                    orgBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    if (expanded) {
                        const searchInput = orgList.querySelector('.custom-dropdown-input');
                        if (searchInput) {
                            searchInput.value = '';
                            orgList.querySelectorAll('a').forEach(a => a.style.display = '');
                            const noMsg = orgList.querySelector('.no-results-message');
                            if (noMsg) noMsg.style.display = 'none';
                            setTimeout(() => searchInput.focus(), 30);
                        }
                    }
                });
            }

            window.filterOrgOptions = function(ev) {
                const filter = (ev.target.value || '').toUpperCase();
                const links = orgList.querySelectorAll('a');
                let count = 0;
                links.forEach(a => {
                    const txt = (a.getAttribute('data-text') || a.textContent || '').toUpperCase();
                    const show = txt.includes(filter);
                    a.style.display = show ? '' : 'none';
                    if (show) count++;
                });
                let noMsg = orgList.querySelector('.no-results-message');
                if (count === 0 && filter) {
                    if (!noMsg) {
                        noMsg = document.createElement('div');
                        noMsg.className = 'no-results-message';
                        Object.assign(noMsg.style, {
                            padding: '12px 16px',
                            color: '#6c757d',
                            fontStyle: 'italic'
                        });
                        noMsg.textContent = 'Tidak ada organisasi yang ditemukan';
                        orgList.appendChild(noMsg);
                    }
                    noMsg.style.display = 'block';
                } else if (noMsg) {
                    noMsg.style.display = 'none';
                }
            };

            orgList.addEventListener('click', function(event) {
                const a = event.target.closest('a');
                if (!a) return;
                event.preventDefault();

                const val = a.getAttribute('data-value');
                const text = a.getAttribute('data-text');

                orgInput.value = val;
                selectedOrgSpan.textContent = (text === 'Lainnya') ? 'Lainnya (isi manual)' : text;

                orgList.classList.remove('show-dropdown');
                orgBtn.setAttribute('aria-expanded', 'false');

                if (val === 'lainnya') {
                    orgManualWrap.classList.remove('d-none');
                    orgManualInput.disabled = false;
                    orgManualInput.required = true;
                    setTimeout(() => orgManualInput.focus(), 60);
                } else {
                    orgManualWrap.classList.add('d-none');
                    orgManualInput.disabled = true;
                    orgManualInput.required = false;
                    orgManualInput.value = '';
                }
            });

            window.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown-container')) {
                    if (orgList.classList.contains('show-dropdown')) {
                        orgList.classList.remove('show-dropdown');
                        orgBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // ========== SIGNATURE PAD ==========
            let signaturePad;
            const canvas = document.getElementById('signature-pad');

            function resizeSignatureCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                const heightCss = 240;
                canvas.width = Math.floor(rect.width * ratio);
                canvas.height = Math.floor(heightCss * ratio);
                const ctx = canvas.getContext('2d');
                ctx.scale(ratio, ratio);
                if (signaturePad && !signaturePad.isEmpty()) {
                    const data = signaturePad.toData();
                    signaturePad.clear();
                    signaturePad.fromData(data);
                }
            }

            if (window.SignaturePad && canvas) {
                signaturePad = new SignaturePad(canvas, {
                    minWidth: 0.5,
                    maxWidth: 2.5,
                    throttle: 16
                });
                resizeSignatureCanvas();
                window.addEventListener('resize', () => {
                    clearTimeout(window.__sigRT);
                    window.__sigRT = setTimeout(resizeSignatureCanvas, 120);
                });
            } else {
                showError('Gagal memuat komponen TTD Digital. Muat ulang halaman.');
            }

            document.getElementById('sigClearBtn')?.addEventListener('click', () => signaturePad?.clear());
            document.getElementById('sigUndoBtn')?.addEventListener('click', () => {
                if (!signaturePad) return;
                const data = signaturePad.toData();
                if (data && data.length) {
                    data.pop();
                    signaturePad.fromData(data);
                }
            });

            // ========== SUBMIT ==========
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                hideError();

                if (!orgInput.value) return showError('Silakan pilih organisasi.');
                if (orgInput.value === 'lainnya' && !(orgManualInput.value || '').trim()) {
                    return showError('Silakan isi nama organisasi pada kolom yang muncul.');
                }
                if (!signaturePad || signaturePad.isEmpty()) {
                    return showError('Silakan bubuhkan TTD Digital pada kotak yang tersedia.');
                }

                // set hidden TTD dulu
                const dataURL = signaturePad.toDataURL('image/png'); // "data:image/png;base64,..."
                document.getElementById('ttd_path').value = dataURL;

                // batas ~2MB (opsional)
                const prefixLen = 'data:image/png;base64,'.length;
                const approxBytes = Math.ceil((dataURL.length - prefixLen) * 3 / 4);
                if (approxBytes > 2 * 1024 * 1024) return showError('Ukuran TTD melebihi 2 MB.');

                // baru kumpulkan FormData
                const formData = new FormData(form);

                try {
                    const res = await fetch("{{ route('presensi.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const ct = res.headers.get('content-type') || '';
                    const payload = ct.includes('application/json') ? await res.json() : {
                        ok: false
                    };
                    if (!res.ok || !payload.ok) throw new Error(payload?.message ||
                        'Gagal menyimpan presensi.');

                    // buka sukma
                    window.open(sukmaUrl, '_blank', 'noopener,noreferrer');

                    window.location.href = payload.redirect;
                } catch (err) {
                    showError(err.message || 'Terjadi kesalahan.');
                }
            });

        })();
    </script>

@endsection
