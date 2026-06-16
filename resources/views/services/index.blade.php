@extends('layouts.app')
@section('title', 'Data Service')

@section('content')
<div class="bg-white p-4 rounded-3 shadow-sm border">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small py-2 px-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-screwdriver-wrench text-primary me-2"></i>Data Work Order Service</h4>
            <p class="text-muted small m-0">Daftar antrean perbaikan dan riwayat keluhan kendaraan masuk Mulia Jaya Motor.</p>
        </div>
        <button class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahService">
            <i class="fa-solid fa-plus"></i> Buat Perintah Kerja
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-md-4 ms-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchService" class="form-control border-start-0 ps-0" placeholder="Cari nomor WO, nopol, atau nama...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0 fs-7" id="tableService">
            <thead class="table-light">
                <tr>
                    <th>No. Service / WO</th>
                    <th>Tanggal</th>
                    <th>Pelanggan & Nopol</th>
                    <th>Keluhan Utama</th>
                    <th>Status Kerja</th>
                    <th class="text-center" style="width: 130px;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                <tr>
                    <td class="fw-bold text-primary target-wo">{{ $s->nomor_service }}</td>
                    <td>{{ date('d/m/Y', strtotime($s->tanggal)) }}</td>
                    <td>
                        <div class="fw-semibold text-dark target-nama">{{ $s->customer->nama_pelanggan ?? 'Umum' }}</div>
                        <span class="badge bg-secondary small mt-0.5 target-nopol">{{ $s->vehicle->nomor_polisi ?? '-' }}</span>
                    </td>
                    <td class="text-muted text-wrap" style="max-width: 250px;">{{ $s->keluhan }}</td>
                    <td>
                        <span class="badge {{ $s->status == 'Selesai' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} px-2 py-1.5 fw-medium">
                            <i class="fa-solid {{ $s->status == 'Selesai' ? 'fa-circle-check' : 'fa-spinner fa-spin' }} me-1"></i>{{ $s->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-light border text-primary py-1 px-2" title="Update Progress / Selesai" data-bs-toggle="modal" data-bs-target="#modalEditService{{ $s->id }}"><i class="fa-solid fa-user-gear"></i></button>
                            <button class="btn btn-sm btn-light border text-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalHapusService{{ $s->id }}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditService{{ $s->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-screwdriver-wrench me-2"></i>Update Progress Kerja: {{ $s->nomor_service }}</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('services.update', $s->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4">
                                    <div class="mb-3 bg-light p-3 rounded rounded-3 border">
                                        <span class="small text-muted d-block">Keluhan Masuk Pelanggan:</span>
                                        <p class="m-0 fw-medium text-dark small">"{{ $s->keluhan }}"</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Hasil Diagnosa Mekanik</label>
                                        <textarea name="diagnosa" class="form-control form-control-sm" rows="2" placeholder="Contoh: Kampas rem depan aus, oli mesin rembes">{{ $s->diagnosa }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Pekerjaan / Tindakan yang Dilakukan</label>
                                        <textarea name="pekerjaan_dilakukan" class="form-control form-control-sm" rows="2" placeholder="Contoh: Mengganti kampas rem depan, tune up mesin">{{ $s->pekerjaan_dilakukan }}</textarea>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small fw-medium text-dark">Status Pengerjaan</label>
                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="Proses" {{ $s->status == 'Proses' ? 'selected' : '' }}>Masih Dalam Proses Perbaikan</option>
                                            <option value="Selesai" {{ $s->status == 'Selesai' ? 'selected' : '' }}>Selesai / Mobil Siap Keluar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light p-3">
                                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">Simpan Progress</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalHapusService{{ $s->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body p-4 text-center">
                                <i class="fa-solid fa-circle-exclamation text-danger fs-1 mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Batalkan WO Service?</h6>
                                <p class="text-muted small mb-4">Tindakan ini akan menghapus data perintah kerja <strong>{{ $s->nomor_service }}</strong>.</p>
                                <form action="{{ route('services.destroy', $s->id) }}" method="POST">
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
                        <i class="fa-solid fa-list-check fs-2 mb-2 d-block text-black-50"></i>
                        Belum ada antrean kendaraan masuk hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahService" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-file-signature me-2"></i>Buat Perintah Kerja (Work Order)</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Pilih Pelanggan <span class="text-danger">*</span></label>
                        <select name="customer_id" id="select_customer" class="form-select form-select-sm" required>
                            <option value="" disabled selected>-- Pilih Nama Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->nama_pelanggan }} ({{ $c->nomor_hp }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Pilih Mobil / Nopol <span class="text-danger">*</span></label>
                        <select name="vehicle_id" id="select_vehicle" class="form-select form-select-sm" required>
                            <option value="" disabled selected>-- Pilih Mobil --</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" data-customer="{{ $v->customer_id }}">{{ $v->nomor_polisi }} - {{ $v->merk_mobil }} {{ $v->tipe_mobil }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Keluhan Utama Kendaraan <span class="text-danger">*</span></label>
                        <textarea name="keluhan" class="form-control form-control-sm" rows="3" placeholder="Contoh: Rem bunyi mencicit saat diinjak, setir lari ke kiri..." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium text-dark">Catatan Tambahan Diagnosa Awal (Opsional)</label>
                        <textarea name="diagnosa" class="form-control form-control-sm" rows="2" placeholder="Catatan sekunder SA / penerima mobil..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">Buka Perintah Kerja</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // JAVASCRIPT: Otomatis memfilter daftar mobil dropdown agar HANYA memunculkan mobil milik customer yang dipilih
    document.getElementById('select_customer').addEventListener('change', function() {
        let customerId = this.value;
        let vehicleSelect = document.getElementById('select_vehicle');
        let options = vehicleSelect.querySelectorAll('option');
        
        vehicleSelect.value = ""; // Reset pilihan mobil
        options.forEach(function(opt) {
            if (opt.value === "") return;
            if (opt.getAttribute('data-customer') === customerId) {
                opt.style.display = "block";
            } else {
                opt.style.display = "none";
            }
        });
    });

    // Fitur pencarian data service real-time
    document.getElementById('searchService').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableService tbody tr');
        
        rows.forEach(function(row) {
            let wo = row.querySelector('.target-wo')?.textContent.toLowerCase() || '';
            let nama = row.querySelector('.target-nama')?.textContent.toLowerCase() || '';
            let nopol = row.querySelector('.target-nopol')?.textContent.toLowerCase() || '';
            
            if (wo.indexOf(value) > -1 || nama.indexOf(value) > -1 || nopol.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush