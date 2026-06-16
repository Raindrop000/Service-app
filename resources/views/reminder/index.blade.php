@extends('layouts.app')
@section('title', 'Reminder Service')

@section('content')
<div class="bg-white p-4 rounded-3 shadow-sm border">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-bell text-warning me-2"></i>Reminder & Retensi Pelanggan</h4>
            <p class="text-muted small m-0">Daftar kendaraan yang tidak berkunjung lebih dari 6 bulan. Klik tombol WA untuk mengirim pesan pengingat otomatis.</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-md-4 ms-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="searchReminder" class="form-control border-start-0 ps-0" placeholder="Cari nopol atau nama pemilik...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0 fs-7" id="tableReminder">
            <thead class="table-light">
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>No. HP / WhatsApp</th>
                    <th>Kendaraan / Nopol</th>
                    <th>Tanggal Service Terakhir</th>
                    <th>Status Jeda Waktu</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reminders as $r)
                @php
                    // Hitung selisih bulan secara dinamis untuk tampilan informasi kasir
                    $tglTerakhir = \Carbon\Carbon::parse($r->tanggal);
                    $selisihBulan = $tglTerakhir->diffInMonths(\Carbon\Carbon::now());
                    
                    // Membuat teks template pesan WhatsApp otomatis
                    $pesanWA = "Halo Bos *{$r->customer->nama_pelanggan}*,\r\n\r\nKami dari *Mulia Jaya Motor* ingin menginfokan bahwa mobil dengan nopol *{$r->vehicle->nomor_polisi}* ({$r->vehicle->merk_mobil} {$r->vehicle->tipe_mobil}) sudah waktunya melakukan perawatan rutin kembali berkala. \r\n\r\nTerakhir service di bengkel kami pada tanggal " . date('d-m-Y', strtotime($r->tanggal)) . " (Sudah sekitar {$selisihBulan} bulan yang lalu).\r\n\r\nYuk booking jadwal service hari ini agar performa mobil Bos tetap terjaga dan selalu aman di jalan! Terima kasih. 🙏🔥";
                    
                    // Format nomor HP ke standar internasional WhatsApp (62)
                    $noHp = $r->customer->nomor_hp;
                    if (str_starts_with($noHp, '0')) {
                        $noHp = '62' . substr($noHp, 1);
                    }
                @endphp
                <tr>
                    <td class="fw-semibold text-dark target-nama">{{ $r->customer->nama_pelanggan }}</td>
                    <td>
                        <span class="text-muted"><i class="fa-solid fa-phone me-1 text-black-50"></i>{{ $r->customer->nomor_hp }}</span>
                    </td>
                    <td>
                        <div class="fw-medium target-nopol">{{ $r->vehicle->nomor_polisi }}</div>
                        <span class="small text-muted">{{ $r->vehicle->merk_mobil }} - {{ $r->vehicle->tipe_mobil }}</span>
                    </td>
                    <td class="fw-medium text-danger">
                        <i class="fa-solid fa-calendar-day me-1"></i>{{ date('d-m-Y', strtotime($r->tanggal)) }}
                    </td>
                    <td>
                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-semibold">
                            Belum Service {{ $selisihBulan }} Bulan
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="https://api.whatsapp.com/send?phone={{ $noHp }}&text={{ rawurlencode($pesanWA) }}" target="_blank" class="btn btn-sm btn-success px-2.5 shadow-sm d-inline-flex align-items-center gap-1.5 fw-medium">
                            <i class="fa-brands fa-whatsapp fs-6"></i> Kirim WA
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fa-solid fa-circle-check fs-2 mb-2 d-block text-success"></i>
                        Hebat! Semua database mobil pelanggan rajin service rutin (Di bawah 6 bulan).
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fitur pencarian reminder real-time yang aman dari eror data kosong
    document.getElementById('searchReminder').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableReminder tbody tr');
        
        rows.forEach(function(row) {
            // Cek apakah ini baris data atau baris "Data Kosong"
            let namaTarget = row.querySelector('.target-nama');
            let nopolTarget = row.querySelector('.target-nopol');
            
            // Jika baris kosong (tidak punya class target-nama), biarkan saja tetap tampil
            if (!namaTarget && !nopolTarget) {
                return;
            }
            
            let nama = namaTarget ? namaTarget.textContent.toLowerCase() : '';
            let nopol = nopolTarget ? nopolTarget.textContent.toLowerCase() : '';
            
            // Lakukan pencocokan karakter kata kunci
            if (nama.indexOf(value) > -1 || nopol.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endpush