@extends('layouts.app')

@section('title', 'Database Pelanggan')

@section('content')
<div class="bg-white p-4 rounded-3 shadow-sm border">
    
    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small py-2 px-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-users text-primary me-2"></i>Database Pelanggan</h4>
            <p class="text-muted small m-0">Manajemen riwayat biodata dan kontak pelanggan Mulia Jaya Motor.</p>
        </div>
        <button class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahCustomer">
            <i class="fa-solid fa-user-plus"></i> Tambah Pelanggan
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-md-4 ms-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchCustomer" class="form-control border-start-0 ps-0" placeholder="Cari nama atau nomor HP...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0 fs-7" id="tableCustomer">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Nomor HP / WA</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td class="text-muted fw-bold">#CUST-{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="fw-semibold text-dark target-nama">{{ $c->nama_pelanggan }}</td>
                    <td class="target-hp">
                        <a href="https://wa.me/{{ str_starts_with($c->nomor_hp, '0') ? '62'.substr($c->nomor_hp, 1) : $c->nomor_hp }}" target="_blank" class="text-decoration-none text-success fw-medium">
                            <i class="fa-brands fa-whatsapp me-1"></i>{{ $c->nomor_hp }}
                        </a>
                    </td>
                    <td>{{ $c->email ?? '-' }}</td>
                    <td class="text-muted small">{{ Str::limit($c->alamat, 50) }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <!-- Tombol Edit Pemicu Modal -->
                            <button class="btn btn-sm btn-light border text-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditCustomer{{ $c->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <!-- Tombol Hapus Pemicu Modal -->
                            <button class="btn btn-sm btn-light border text-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalHapusCustomer{{ $c->id }}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT DATA PER PELANGGAN -->
                <div class="modal fade" id="modalEditCustomer{{ $c->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                                <h6 class="modal-title fw-bold"><i class="fa-solid fa-user-pen me-2"></i>Edit Data Pelanggan</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('customers.update', $c->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Nama Lengkap</label>
                                        <input type="text" name="nama_pelanggan" class="form-control form-control-sm" value="{{ $c->nama_pelanggan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Nomor HP / WhatsApp</label>
                                        <input type="tel" name="nomor_hp" class="form-control form-control-sm" value="{{ $c->nomor_hp }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-medium text-dark">Alamat Email</label>
                                        <input type="email" name="email" class="form-control form-control-sm" value="{{ $c->email }}">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small fw-medium text-dark">Alamat Rumah</label>
                                        <textarea name="alamat" class="form-control form-control-sm" rows="3">{{ $c->alamat }}</textarea>
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

                <!-- MODAL KONFIRMASI HAPUS PER PELANGGAN -->
                <div class="modal fade" id="modalHapusCustomer{{ $c->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body p-4 text-center">
                                <i class="fa-solid fa-triangle-exclamation text-danger fs-1 mb-3 animate__animated animate__pulse animate__infinite"></i>
                                <h6 class="fw-bold text-dark mb-1">Hapus Data Pelanggan?</h6>
                                <p class="text-muted small mb-4">Tindakan ini akan menghapus permanen data <strong>{{ $c->nama_pelanggan }}</strong> beserta riwayat kendaraannya.</p>
                                <form action="{{ route('customers.destroy', $c->id) }}" method="POST">
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
                        <i class="fa-solid fa-folder-open fs-2 mb-2 d-block text-black-50"></i>
                        Belum ada data pelanggan terdaftar di sistem database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM TAMBAH PELANGGAN -->
<div class="modal fade" id="modalTambahCustomer" tabindex="-1" aria-labelledby="modalTambahCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                <h6 class="modal-title fw-bold" id="modalTambahCustomerLabel"><i class="fa-solid fa-user-plus me-2"></i>Registrasi Pelanggan Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- FORM SEKARANG MENEMBAK ROUTE STORE -->
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pelanggan" class="form-control form-control-sm" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                        <input type="tel" name="nomor_hp" class="form-control form-control-sm" placeholder="Contoh: 08123456xxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Alamat Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Contoh: budi@gmail.com">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium text-dark">Alamat Rumah</label>
                        <textarea name="alamat" class="form-control form-control-sm" rows="3" placeholder="Tuliskan alamat lengkap tempat tinggal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchCustomer').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableCustomer tbody tr');
        
        rows.forEach(function(row) {
            let nama = row.querySelector('.target-nama')?.textContent.toLowerCase() || '';
            let hp = row.querySelector('.target-hp')?.textContent.toLowerCase() || '';
            
            if (nama.indexOf(value) > -1 || hp.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush