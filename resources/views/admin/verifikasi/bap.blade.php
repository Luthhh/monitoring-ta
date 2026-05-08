@extends('layouts.admin')

@section('title', 'Upload BAP')

@section('page-content')

<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<div class="box">
    <div class="box-header">
        <h3>📄 Daftar Tunggu Upload BAP</h3>
        <span class="badge" style="background: #f6c23e; color: black;">{{ $baps->total() }} Belum Diunggah</span>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jenis Milestone</th>
                <th>Tgl Disetujui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($baps as $m)
            <tr>
                <td>{{ $m->tugasAkhir->mahasiswa->nim }}</td>
                <td style="text-align: left;">{{ $m->tugasAkhir->mahasiswa->user->name }}</td>
                <td>{{ $m->jenis_milestone }}</td>
                <td>{{ \Carbon\Carbon::parse($m->tanggal_disetujui)->format('d M Y') }}</td>
                <td>
                    <button class="btn-icon btn-acc" style="background:#198754; color: white; border: none;" title="Upload BAP" 
                        onclick="openUploadBapModal({{ $m->id }}, '{{ $m->jenis_milestone }}', '{{ addslashes($m->tugasAkhir->mahasiswa->user->name) }}')">
                        <i class="fas fa-upload"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center; color: #999; padding: 20px;">Semua BAP sudah terunggah atau belum ada milestone yang disetujui.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $baps->links() }}
    </div>
</div>

@push('modals')
{{-- Modal Upload BAP --}}
<div class="modal fade" id="uploadBapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" id="formUploadBap" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload BAP Milestone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p>Mahasiswa: <strong id="bap-mhs-name"></strong></p>
                        <p>Milestone: <strong id="bap-milestone-name"></strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File BAP (PDF)</label>
                        <input type="file" name="file_bap" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Maksimal 4MB. Format: .pdf</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan & Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
function openUploadBapModal(id, milestone, name) {
    const form = document.getElementById('formUploadBap');
    form.action = "{{ url('/admin/upload-bap') }}/" + id;
    document.getElementById('bap-mhs-name').innerText = name;
    document.getElementById('bap-milestone-name').innerText = milestone;
    
    const modal = new bootstrap.Modal(document.getElementById('uploadBapModal'));
    modal.show();
}
</script>
<style>
.topbar { display: flex; justify-content: space-between; margin-bottom: 25px; align-items: center; }
.box { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
.box-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: #f8f9fc; padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600; text-align: center; }
.table-custom td { padding: 12px; border-top: 1px solid #eee; font-size: 13px; text-align: center; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 10px; text-decoration: none !important; }
</style>
@endpush

@endsection
