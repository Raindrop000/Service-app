@extends('layouts.app')
@section('title', 'Database Kendaraan')

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
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-car text-primary me-2"></i>Database Kendaraan</h4>
            <p class="text-muted small m-0">Manajemen data mobil pelanggan Mulia Jaya Motor.</p>
        </div>
        <button class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahVehicle">
            <i class="fa-solid fa-plus"></i> Tambah Kendaraan
        </button>
    </div>

    <!-- Filter & Cari -->
    <div class="row mb-3">
        <div class="col-12 col-md-4 ms-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchVehicle" class="form-control border-start-0 ps-0" placeholder="Cari nopol, nama pemilik, tipe...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0 fs-7" id="tableVehicle">
            <thead class="table-light">
                <tr>
                    <th>No. Polisi</th>
                    <th>Nama Pemilik</th>
                    <th>Merk / Tipe Mobil</th>
                    <th>Tahun</th>
                    <th>No. Rangka / Mesin</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $v)
                <tr>
                    <td><span class="badge bg-dark px-2 py-1.5 fw-bold target-nopol">{{ $v->nomor_polisi }}</span></td>
                    <td class="fw-semibold text-dark target-pemilik">{{ $v->customer->nama_pelanggan ?? 'Umum / Tanpa Nama' }}</td>
                    <td class="target-tipe">{{ $v->merk_mobil }} - {{ $v->tipe_mobil }}</td>
                    <td>{{ $v->tahun ?? '-' }}</td>
                    <td class="small text-muted">{{ $v->nomor_rangka ?? '-' }} / {{ $v->nomor_mesin ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-light border text-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditVehicle{{ $v->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-light border text-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalHapusVehicle{{ $v->id }}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT KENDARAAN -->
                <div class="modal fade" id="modalEditVehicle{{ $v->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-car-side me-2"></i>Edit Data Kendaraan</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('vehicles.update', $v->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Pilih Pemilik (Customer)</label>
                                        <select name="customer_id" class="form-select form-select-sm" required>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}" {{ $v->customer_id == $c->id ? 'selected' : '' }}>{{ $c->nama_pelanggan }} ({{ $c->nomor_hp }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Nomor Polisi (Nopol)</label>
                                        <input type="text" name="nomor_polisi" class="form-control form-control-sm text-uppercase" value="{{ $v->nomor_polisi }}" required>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-medium text-dark">Merk Mobil</label>
                                            <input type="text" name="merk_mobil" class="form-control form-control-sm" value="{{ $v->merk_mobil }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-medium text-dark">Tipe Mobil</label>
                                            <input type="text" name="tipe_mobil" class="form-control form-control-sm" value="{{ $v->tipe_mobil }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small fw-medium text-dark">Tahun Pembuatan</label>
                                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $v->tahun }}">
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

                <!-- MODAL HAPUS KENDARAAN -->
                <div class="modal fade" id="modalHapusVehicle{{ $v->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body p-4 text-center">
                                <i class="fa-solid fa-triangle-exclamation text-danger fs-1 mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Hapus Kendaraan?</h6>
                                <p class="text-muted small mb-4">Menghapus nopol <strong>{{ $v->nomor_polisi }}</strong> milik {{ $v->customer->nama_pelanggan ?? '' }}.</p>
                                <form action="{{ route('vehicles.destroy', $v->id) }}" method="POST">
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
                        <i class="fa-solid fa-car-rear fs-2 mb-2 d-block text-black-50"></i>
                        Belum ada data kendaraan terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM TAMBAH KENDARAAN -->
<div class="modal fade" id="modalTambahVehicle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-car text-primary me-2"></i>Registrasi Kendaraan Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Pilih Pemilik (Customer) <span class="text-danger">*</span></label>
                        <select name="customer_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>-- Pilih Pemilik Mobil --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->nama_pelanggan }} ({{ $c->nomor_hp }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Nomor Polisi (Nopol) <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_polisi" class="form-control form-control-sm text-uppercase" placeholder="Contoh: B 1234 ABC" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-medium text-dark">Merk Mobil <span class="text-danger">*</span></label>
                            <input type="text" name="merk_mobil" class="form-control form-control-sm" placeholder="Contoh: Toyota / Honda" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-medium text-dark">Tipe / Seri Mobil <span class="text-danger">*</span></label>
                            <input type="text" name="tipe_mobil" class="form-control form-control-sm" placeholder="Contoh: Avanza G / Jazz" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-4">
                            <label class="form-label small fw-medium text-dark">Tahun Produksi</label>
                            <input type="number" name="tahun" class="form-control form-control-sm" placeholder="2018">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-medium text-dark">No. Rangka</label>
                            <input type="text" name="nomor_rangka" class="form-control form-control-sm" placeholder="Opsional">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-medium text-dark">No. Mesin</label>
                            <input type="text" name="nomor_mesin" class="form-control form-control-sm" placeholder="Opsional">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">Simpan Kendaraan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fitur pencarian kendaraan real-time
    document.getElementById('searchVehicle').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableVehicle tbody tr');
        
        rows.forEach(function(row) {
            let nopol = row.querySelector('.target-nopol')?.textContent.toLowerCase() || '';
            let pemilik = row.querySelector('.target-pemilik')?.textContent.toLowerCase() || '';
            let tipe = row.querySelector('.target-tipe')?.textContent.toLowerCase() || '';
            
            if (nopol.indexOf(value) > -1 || pemilik.indexOf(value) > -1 || tipe.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush