@extends('layouts.app')
@section('title', 'Data Invoices')

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
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Faktur & Invoices Pembayaran</h4>
            <p class="text-muted small m-0">Rekapitulasi tagihan kasir, hitung biaya jasa mekanik, dan penggunaan suku cadang.</p>
        </div>
        <!-- Tombol Buat Nota -->
        <button class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahInvoice">
            <i class="fa-solid fa-calculator"></i> Proses Transaksi Baru
        </button>
    </div>

    <!-- Kolom Pencarian Nota -->
    <div class="row mb-3">
        <div class="col-12 col-md-4 ms-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchInvoice" class="form-control border-start-0 ps-0" placeholder="Cari nomor invoice / nota...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0 fs-7" id="tableInvoice">
            <thead class="table-light">
                <tr>
                    <th>No. Invoice</th>
                    <th>Ref Perintah Kerja</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Jasa</th>
                    <th>Total Part</th>
                    <th class="fw-bold">Grand Total</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="fw-bold text-dark target-inv">{{ $inv->nomor_invoice }}</td>
                    <td class="text-muted small">{{ $inv->service->nomor_service ?? '-' }}</td>
                    <td class="fw-semibold target-nama">{{ $inv->service->customer->nama_pelanggan ?? 'Umum' }}</td>
                    <td>Rp {{ number_format($inv->total_biaya_jasa, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($inv->total_biaya_sparepart, 0, ',', '.') }}</td>
                    <td class="text-success fw-bold fs-6">Rp {{ number_format($inv->grand_total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <!-- Tombol Simulasi Print -->
                            <button class="btn btn-sm btn-danger py-1 px-2" onclick="window.print()" title="Cetak Nota Pembayaran"><i class="fa-solid fa-print"></i></button>
                            <!-- Tombol Hapus Pembukuan -->
                            <button class="btn btn-sm btn-light border text-danger py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalHapusInvoice{{ $inv->id }}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL HAPUS INVOICE -->
                <div class="modal fade" id="modalHapusInvoice{{ $inv->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body p-4 text-center">
                                <i class="fa-solid fa-ban text-danger fs-1 mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Batalkan Transaksi?</h6>
                                <p class="text-muted small mb-4">Membatalkan nota <strong>{{ $inv->nomor_invoice }}</strong> akan menghapus riwayat keuangan dari laporan kasir.</p>
                                <form action="{{ route('invoices.destroy', $inv->id) }}" method="POST">
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
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fa-solid fa-receipt fs-2 mb-2 d-block text-black-50"></i>
                        Belum ada invoice kasir yang diterbitkan hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL FORM PROSES TRANSAKSI BARU -->
<div class="modal fade" id="modalTambahInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--deep-blue);">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-calculator me-2"></i>Hitung Biaya & Terbitkan Nota</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Pilih WO Mobil Selesai <span class="text-danger">*</span></label>
                        <select name="service_id" class="form-select form-select-sm" required>
                            <option value="" disabled selected>-- Pilih Antrean Service Selesai --</option>
                            @forelse($completedServices as $cs)
                                <option value="{{ $cs->id }}">{{ $cs->nomor_service }} - {{ $cs->customer->nama_pelanggan }} ({{ $cs->vehicle->nomor_polisi }})</option>
                            @empty
                                <option value="" disabled>Tidak ada mobil berstatus 'Selesai' yang mengantre tagihan</option>
                            @endforelse
                        </select>
                        <span class="text-muted d-block mt-1" style="font-size: 11px;">*Hanya menampilkan mobil yang status pengerjaannya sudah diubah ke 'Selesai' di menu Data Service.</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-dark">Total Ongkos Jasa Mekanik (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="total_biaya_jasa" class="form-control form-control-sm" placeholder="Contoh: 150000" min="0" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium text-dark">Total Biaya Penggunaan Sparepart (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="total_biaya_sparepart" class="form-control form-control-sm" placeholder="Contoh: 320000" min="0" required>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success px-4 shadow-sm fw-medium">Simpan & Cetak Faktur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fitur pencarian invoice dinamis
    document.getElementById('searchInvoice').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableInvoice tbody tr');
        
        rows.forEach(function(row) {
            let inv = row.querySelector('.target-inv')?.textContent.toLowerCase() || '';
            let nama = row.querySelector('.target-nama')?.textContent.toLowerCase() || '';
            
            if (inv.indexOf(value) > -1 || nama.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush