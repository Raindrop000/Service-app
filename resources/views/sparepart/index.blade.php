@extends('layouts.app')

@section('title', 'Inventori Spareparts')

@section('content')
<div class="bg-white p-4 rounded-3 shadow-sm border">
    
    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small py-2 px-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Stok & Inventori Spareparts</h4>
            <p class="text-muted small m-0">Manajemen kode barang, kontrol jumlah stok gudang, harga beli, dan harga jual suku cadang.</p>
        </div>
        <button class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahSparepart">
            <i class="fa-solid fa-plus"></i> Tambah Part Baru
        </button>
    </div>

    <!-- Pencarian -->
    <div class="row mb-3">
        <div class="col-12 col-md-4 ms-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchSparepart" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode sparepart...">
            </div>
        </div>
    </div>

    <!-- Tabel Data Spareparts -->
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0 fs-7" id="tableSparepart">
            <thead class="table-light">
                <tr>
                    <th style="width: 150px;">Kode Part</th>
                    <th>Nama Sparepart</th>
                    <th>Stok Gudang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spareparts as $sp)
                <tr>
                    <td class="fw-bold text-dark target-kode">{{ $sp->kode_sparepart }}</td>
                    <td class="fw-semibold target-nama">{{ $sp->nama_sparepart }}</td>
                    <td>
                        <span class="badge {{ $sp->stok <= 5 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} px-2.5 py-1.5 fw-medium">
                            {{ $sp->stok }} Pcs
                        </span>
                    </td>
                    <td class="text-muted">Rp {{ number_format($sp->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-primary fw-bold">Rp {{ number_format($sp->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-light border text-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditSparepart{{ $sp->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <!-- Tombol Hapus -->
                            <button class="btn btn-sm btn-light border text-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalHapusSparepart{{ $sp->id }}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT SPAREPART -->
                <div class="modal fade" id="modalEditSparepart{{ $sp->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-box-open me-2"></i>Edit Suku Cadang</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('spareparts.update', $sp->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Kode Sparepart</label>
                                        <input type="text" name="kode_sparepart" class="form-control form-control-sm text-uppercase" value="{{ $sp->kode_sparepart }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Nama Sparepart</label>
                                        <input type="text" name="nama_sparepart" class="form-control form-control-sm" value="{{ $sp->nama_sparepart }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Jumlah Stok Gudang</label>
                                        <input type="number" name="stok" class="form-control form-control-sm" value="{{ $sp->stok }}" required>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-medium text-dark">Harga Beli (Modal)</label>
                                            <input type="number" name="harga_beli" class="form-control form-control-sm" value="{{ $sp->harga_beli }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-medium text-dark">Harga Jual Konsumen</label>
                                            <input type="number" name="harga_jual" class="form-control form-control-sm" value="{{ $sp->harga_jual }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light p-3">
                                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-warning px-4 shadow-sm fw-medium">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MODAL HAPUS SPAREPART -->
                <div class="modal fade" id="modalHapusSparepart{{ $sp->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body p-4 text-center">
                                <i class="fa-solid fa-trash-can text-danger fs-1 mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Hapus Sparepart?</h6>
                                <p class="text-muted small mb-4">Tindakan ini akan menghapus permanent kode <strong>{{ $sp->kode_sparepart }}</strong> dari daftar inventori.</p>
                                <form action="{{ route('spareparts.destroy', $sp->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-danger px-3 shadow-sm">Ya, Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fa-solid fa-boxes-packing fs-2 mb-2 d-block text-black-50"></i>
                        Gudang suku cadang masih kosong melompong.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM TAMBAH SPAREPART -->
<div class="modal fade" id="modalTambahSparepart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-box-open me-2"></i>Tambah Suku Cadang Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('spareparts.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Kode Sparepart <span class="text-danger">*</span></label>
                        <input type="text" name="kode_sparepart" class="form-control form-control-sm text-uppercase" placeholder="Contoh: OLI-SPX2-1L" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Nama Sparepart <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sparepart" class="form-control form-control-sm" placeholder="Contoh: Oli Ahm SPX2 1 Liter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Stok Gudang Awal <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control form-control-sm" min="0" placeholder="0" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-medium text-dark">Harga Beli (Modal) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_beli" class="form-control form-control-sm" placeholder="Rp" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-medium text-dark">Harga Jual Konsumen <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jual" class="form-control form-control-sm" placeholder="Rp" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">Simpan Part</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fitur Pencarian Real-time Suku Cadang
    document.getElementById('searchSparepart').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableSparepart tbody tr');
        
        rows.forEach(function(row) {
            let kode = row.querySelector('.target-kode')?.textContent.toLowerCase() || '';
            let nama = row.querySelector('.target-nama')?.textContent.toLowerCase() || '';
            
            if (kode.indexOf(value) > -1 || nama.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush