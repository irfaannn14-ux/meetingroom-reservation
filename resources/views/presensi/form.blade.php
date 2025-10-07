@extends('layout.main')

@section('title', 'Form Presensi')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <div class="card p-4 shadow-lg border-0">
        <h4 class="mb-4 text-center">Form Presensi (ID: {{ $id }})</h4>
        <form id="formPresensi" enctype="multipart/form-data" onsubmit="return handleSubmit(event)">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan" id="jabatanSelect" class="form-select" required onchange="handleJabatanChange()">
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="OPD">OPD</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Organisasi</label>
                <select name="organisasi" id="organisasiSelect" class="form-select" required>
                    <option value="">-- Pilih Organisasi --</option>
                    <option value="Dinas Mancing">Dinas Mancing</option>
                    <option value="Dinas Pendidikan">Dinas Pendidikan</option>
                    <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                    <option value="Eksternal">Eksternal</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">TTD Digital (PDF)</label>
                <input type="file" name="ttd" class="form-control" accept="application/pdf" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim Presensi</button>
        </form>
    </div>
</div>

<script>
    function handleJabatanChange() {
        const jabatan = document.getElementById('jabatanSelect').value;
        const orgSelect = document.getElementById('organisasiSelect');

        for (let i = 0; i < orgSelect.options.length; i++) {
            orgSelect.options[i].style.display = 'block';
        }

        if (jabatan === 'OPD') {
            for (let i = 0; i < orgSelect.options.length; i++) {
                if (orgSelect.options[i].value === 'Eksternal') {
                    orgSelect.options[i].style.display = 'none';
                }
            }
            orgSelect.value = "";
            orgSelect.disabled = false;

        } else if (jabatan === 'Lainnya') {
            orgSelect.value = "Eksternal";
            orgSelect.disabled = true;
        } else {
            orgSelect.value = "";
            orgSelect.disabled = false;
        }
    }

    function handleSubmit(e) {
        e.preventDefault();
        alert("Presensi berhasil!");
        window.open('https://sukma.jatimprov.go.id/fe', '_blank');
        window.location.href = '/history';
        return false;
    }
</script>
@endsection