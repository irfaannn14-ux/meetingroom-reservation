@extends('layout.main')
@section('title', 'Form Presensi')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    :root {
        --primary-color: #1e3a8a;
        --secondary-color: #3b82f6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --light-color: #f8fafc;
        --dark-color: #1e293b;
        --border-radius: 12px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }
    
    body {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(135deg, #e6f0ff 0%, #f0f7ff 100%);
        color: var(--dark-color);
        padding-top: 60px;
        padding-left: 60px;
        min-height: 100vh;
    }
    
    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .form-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        max-width: 650px;
        margin: 0 auto;
        border: 1px solid #e2e8f0;
    }
    
    .form-header {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        color: white;
        padding: 24px 32px;
        position: relative;
    }
    
    .form-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--secondary-color);
    }
    
    .form-body {
        padding: 32px;
    }
    
    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-align: center;
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: center;
    }
    
    .form-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 300;
        font-size: 1.05rem;
        text-align: center;
    }
    
    .form-section {
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px dashed #cbd5e1;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }
    
    .form-control {
        border: 1px solid #cbd5e1;
        padding: 12px 16px;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        transition: var(--transition);
        height: 48px;
    }
    
    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        outline: none;
    }
    
    .form-control::placeholder {
        color: #94a3b8;
        font-style: italic;
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
        padding: 16px;
        height: auto;
    }
    
    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.85rem;
        margin-top: 4px;
        display: block;
    }
    
    .input-hint {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 6px;
        display: block;
        line-height: 1.4;
    }
    
    .required-badge {
        color: var(--danger-color);
        font-weight: bold;
        margin-left: 4px;
    }
    
    .custom-dropdown-container {
        position: relative;
        margin-top: 8px;
    }
    
    .custom-dropdown-button {
        background: white;
        border: 1px solid #cbd5e1;
        color: var(--dark-color);
        padding: 12px 16px;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 48px;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }
    
    .custom-dropdown-button:hover,
    .custom-dropdown-button:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    
    .custom-dropdown-button i {
        font-size: 0.9rem;
        margin-left: 8px;
        transition: transform 0.3s ease;
    }
    
    .dropdown-open .custom-dropdown-button i {
        transform: rotate(180deg);
    }
    
    .custom-dropdown-content {
        display: none;
        position: absolute;
        background: white;
        width: 100%;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 4px;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .dropdown-open .custom-dropdown-content {
        display: block;
        animation: dropdownSlide 0.2s ease;
    }
    
    @keyframes dropdownSlide {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .custom-dropdown-input {
        width: calc(100% - 32px);
        margin: 0 16px 12px;
        padding: 10px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
    }
    
    .org-item {
        padding: 12px 16px;
        display: block;
        color: var(--dark-color);
        text-decoration: none;
        transition: var(--transition);
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }
    
    .org-item:last-child {
        border-bottom: none;
    }
    
    .org-item:hover {
        background-color: #f0f7ff;
        color: var(--primary-color);
    }
    
    .org-item.selected {
        background-color: #dbeafe;
        color: var(--primary-color);
    }
    
    .signature-pad-wrapper {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        position: relative;
        padding: 20px;
        min-height: 240px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }
    
    .signature-pad-wrapper:hover {
        border-color: var(--secondary-color);
        background: #eff6ff;
    }
    
    .signature-pad-instruction {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 0.85rem;
        color: #64748b;
        background: rgba(255, 255, 255, 0.8);
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        font-weight: 500;
    }
    
    canvas#signature-pad {
        width: 100%;
        height: 200px;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .signature-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        justify-content: center;
    }
    
    .btn-signature {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-signature i {
        font-size: 0.9rem;
    }
    
    .btn-outline-secondary {
        background: white;
        border: 1px solid #cbd5e1;
        color: var(--dark-color);
    }
    
    .btn-outline-secondary:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    
    .btn-outline-danger {
        background: white;
        border: 1px solid #ef4444;
        color: #ef4444;
    }
    
    .btn-outline-danger:hover {
        background: #fee2e2;
        border-color: #f87171;
    }
    
    .btn-submit {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        border: none;
        color: white;
        padding: 14px 24px;
        font-size: 1.05rem;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: var(--transition);
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 6px rgba(30, 58, 138, 0.3);
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(30, 58, 138, 0.4);
    }
    
    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .alert {
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        border: none;
        font-family: 'Poppins', sans-serif;
    }
    
    .alert-danger {
        background-color: #fef2f2;
        color: #b91c1c;
        border-left: 4px solid #ef4444;
    }
    
    .alert i {
        margin-right: 8px;
    }
    
    @media (max-width: 768px) {
        .form-header {
            padding: 20px;
        }
        
        .form-body {
            padding: 24px;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
        
        .signature-pad-wrapper {
            min-height: 200px;
        }
        
        canvas#signature-pad {
            height: 160px;
        }
    }
</style>

<div class="page-container py-4">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="bi bi-file-earmark-check"></i>
                Form Presensi
            </h1>
            <p class="form-subtitle">Silakan lengkapi data presensi Anda</p>
        </div>
        
        <div class="form-body">
            <div id="formAlert" class="alert alert-danger d-none" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span id="alertMessage"></span>
            </div>

            <form id="formPresensi" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pengajuan_id" value="{{ $id }}">
                <input type="hidden" name="ttd_path" id="ttd_path">

                <div class="form-section">
                    <div class="mb-4">
                        <label for="nama" class="form-label">Nama Lengkap <span class="required-badge">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" 
                               placeholder="Masukkan nama lengkap Anda" required>
                        <span class="input-hint">Nama sesuai dengan identitas resmi</span>
                    </div>

                    <div class="mb-4">
                        <label for="fieldJabatan" class="form-label">Jabatan <span class="required-badge">*</span></label>
                        <input type="text" name="jabatan" id="fieldJabatan" class="form-control"
                               placeholder="Tulis jabatan Anda" required>
                        <span class="input-hint">Contoh: Kepala Bagian, Staff Administrasi, dll</span>
                    </div>

                    <div class="mb-4">
                        <label for="no_wa" class="form-label">Nomor WhatsApp <span class="required-badge">*</span></label>
                        <input type="tel" name="no_wa" id="no_wa" class="form-control" 
                               placeholder="Contoh: 081234567890" 
                               pattern="[0-9]{10,15}" 
                               required>
                        <span class="input-hint">Format: 08xxxxxxxxxx (10-15 digit angka tanpa spasi atau karakter khusus)</span>
                    </div>
                </div>

                <div class="form-section">
                    <div class="mb-4">
                        <label for="organisasi" class="form-label">Organisasi <span class="required-badge">*</span></label>
                        <div class="custom-dropdown-container" id="org-dropdown-container">
                            <div class="custom-dropdown-button" onclick="toggleOrgDropdown()">
                                <span id="selected-org">Pilih organisasi...</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div id="org-dropdown-content" class="custom-dropdown-content">
                                <input type="text" class="custom-dropdown-input" onkeyup="filterOrgOptions(event)"
                                       placeholder="Cari organisasi..." autocomplete="off">
                                @foreach ($organizations as $org)
                                    @if (!in_array($org->organization_name, ['ADMIN', 'SUPER ADMIN']))
                                        <a href="#" 
                                           data-value="{{ $org->bkd_organization_id }}"
                                           data-text="{{ $org->organization_name }}"
                                           class="org-item">
                                           {{ $org->organization_name }}
                                        </a>
                                    @endif
                                @endforeach
                                <a href="#" 
                                   data-value="lainnya" 
                                   data-text="Lainnya"
                                   class="org-item">
                                   Lainnya
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="organisasi" id="organisasi-input" required>

                        <div id="org-manual-wrapper" class="mt-3 d-none">
                            <label for="org-manual-input" class="form-label">Nama Organisasi (Manual)</label>
                            <input type="text" name="organisasi_manual" id="org-manual-input" class="form-control"
                                   placeholder="Tulis nama organisasi lengkap Anda">
                            <span class="input-hint">Isi nama organisasi secara lengkap jika memilih opsi "Lainnya"</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="mb-4">
                        <label class="form-label">TTD Digital <span class="required-badge">*</span></label>
                        <div class="signature-pad-wrapper">
                            <span class="signature-pad-instruction">Tanda tangan di sini</span>
                            <canvas id="signature-pad"></canvas>
                        </div>
                        <div class="signature-actions">
                            <button type="button" class="btn-signature btn-outline-secondary" id="sigUndoBtn">
                                <i class="bi bi-arrow-counterclockwise"></i> Undo
                            </button>
                            <button type="button" class="btn-signature btn-outline-danger" id="sigClearBtn">
                                <i class="bi bi-eraser"></i> Bersihkan
                            </button>
                        </div>
                        <span class="input-hint mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Gunakan mouse atau jari (layar sentuh). Ukuran berkas maksimal 2 MB.
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="bi bi-check-circle-fill"></i> Submit Presensi
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Signature Pad library --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
    (function() {
        const form = document.getElementById('formPresensi');
        const alertBox = document.getElementById('formAlert');
        const alertMessage = document.getElementById('alertMessage');

        const orgBtn = document.getElementById('org-dropdown-btn'); 
        const orgList = document.getElementById('org-dropdown-content'); 
        const orgInput = document.getElementById('organisasi-input');
        const selectedOrgSpan = document.getElementById('selected-org');
        const orgManualWrap = document.getElementById('org-manual-wrapper');
        const orgManualInput = document.getElementById('org-manual-input');

        const postUrl = "{{ route('presensi.store') }}";
        const sukmaUrl = 'https://sukma.jatimprov.go.id/fe/survey?idUser=2676';

        function showError(msg) {
            alertMessage.textContent = msg;
            alertBox.classList.remove('d-none');
            alertBox.scrollIntoView({ behavior: 'smooth' });
        }

        function hideError() {
            alertBox.classList.add('d-none');
        }

        function toggleOrgDropdown() {
            const container = document.getElementById('org-dropdown-container');
            container.classList.toggle('dropdown-open');
        }

        window.filterOrgOptions = function(ev) {
            const filter = (ev.target.value || '').toUpperCase();
            const links = orgList.querySelectorAll('.org-item');
            let count = 0;
            links.forEach(a => {
                const txt = (a.getAttribute('data-text') || a.textContent || '').toUpperCase();
                const show = txt.includes(filter);
                a.style.display = show ? '' : 'none';
                if (show) count++;
            });
            
            // Remove existing no-results message if any
            const existingNoResults = orgList.querySelector('.no-results-message');
            if (existingNoResults) {
                existingNoResults.remove();
            }
            
            if (count === 0 && filter) {
                const noMsg = document.createElement('div');
                noMsg.className = 'no-results-message org-item text-center py-3 text-muted';
                noMsg.innerHTML = '<i class="bi bi-search me-1"></i> Tidak ada organisasi yang ditemukan';
                orgList.appendChild(noMsg);
            }
        };

        // Handle organization selection
        orgList.addEventListener('click', function(event) {
            const a = event.target.closest('.org-item');
            if (!a || a.classList.contains('no-results-message')) return;
            event.preventDefault();

            const val = a.getAttribute('data-value');
            const text = a.getAttribute('data-text');

            orgInput.value = val;
            selectedOrgSpan.textContent = (text === 'Lainnya') ? 'Lainnya (isi manual)' : text;

            document.getElementById('org-dropdown-container').classList.remove('dropdown-open');

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

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#org-dropdown-container')) {
                document.getElementById('org-dropdown-container').classList.remove('dropdown-open');
            }
        });

        // ========== SIGNATURE PAD ==========
        let signaturePad;
        const canvas = document.getElementById('signature-pad');

        function resizeSignatureCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = Math.floor(rect.width * ratio);
            canvas.height = Math.floor(200 * ratio); // Fixed height
            const ctx = canvas.getContext('2d');
            ctx.scale(ratio, ratio);
            if (signaturePad && !signaturePad.isEmpty()) {
                const data = signaturePad.toData();
                signaturePad.clear();
                signaturePad.fromData(data);
            }
        }

        if (window.SignaturePad && canvas) {
            // Initialize with white background
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            signaturePad = new SignaturePad(canvas, {
                minWidth: 0.5,
                maxWidth: 2.5,
                penColor: '#1e3a8a',
                backgroundColor: '#ffffff',
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

        document.getElementById('sigClearBtn')?.addEventListener('click', () => {
            if (signaturePad) {
                signaturePad.clear();
                // Redraw white background
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }
        });
        
        document.getElementById('sigUndoBtn')?.addEventListener('click', () => {
            if (!signaturePad) return;
            const data = signaturePad.toData();
            if (data && data.length) {
                data.pop();
                signaturePad.clear();
                // Redraw white background first
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                signaturePad.fromData(data);
            }
        });

        // ========== SUBMIT ==========
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideError();
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;

            // Validate WhatsApp number
            const noWaInput = document.getElementById('no_wa');
            const noWaValue = (noWaInput.value || '').trim();
            const phoneRegex = /^[0-9]{10,15}$/;
            
            if (!noWaValue) {
                showError('Nomor WhatsApp wajib diisi.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
                return;
            }
            
            if (!phoneRegex.test(noWaValue)) {
                showError('Nomor WhatsApp tidak valid. Gunakan 10-15 digit angka tanpa spasi atau karakter khusus.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
                return;
            }

            if (!orgInput.value) {
                showError('Silakan pilih organisasi.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
                return;
            }
            
            if (orgInput.value === 'lainnya' && !(orgManualInput.value || '').trim()) {
                showError('Silakan isi nama organisasi pada kolom yang muncul.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
                return;
            }
            
            if (!signaturePad || signaturePad.isEmpty()) {
                showError('Silakan bubuhkan TTD Digital pada kotak yang tersedia.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
                return;
            }

            // Set hidden TTD
            const dataURL = signaturePad.toDataURL('image/png');
            document.getElementById('ttd_path').value = dataURL;

            // Check TTD size (approximate)
            const prefixLen = 'data:image/png;base64,'.length;
            const approxBytes = Math.ceil((dataURL.length - prefixLen) * 3 / 4);
            if (approxBytes > 2 * 1024 * 1024) {
                showError('Ukuran TTD melebihi 2 MB. Silakan buat tanda tangan yang lebih sederhana.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
                return;
            }

            const formData = new FormData(form);

            try {
                const res = await fetch(postUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const payload = await res.json();
                if (!res.ok || !payload.ok) {
                    throw new Error(payload?.message || 'Gagal menyimpan presensi.');
                }

                // Open sukma in new tab
                window.open(sukmaUrl, '_blank', 'noopener,noreferrer');

                // Show success message before redirect
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success mt-3';
                successAlert.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Presensi berhasil! Mengalihkan ke halaman detail...';
                form.parentNode.insertBefore(successAlert, form);
                
                setTimeout(() => {
                    window.location.href = payload.redirect;
                }, 2000);
                
            } catch (err) {
                showError(err.message || 'Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Submit Presensi';
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            // Set canvas white background initially
            if (canvas) {
                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }
        });
    })();
</script>
@endsection