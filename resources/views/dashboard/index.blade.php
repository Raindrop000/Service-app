@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white p-4 rounded-3 shadow-sm d-flex align-items-center justify-content-between border">
            <div>
                <span class="text-muted small fw-medium">Total Pelanggan</span>
                <h3 class="fw-bold mt-1 mb-0">{{ $totalPelanggan }}</h3>
            </div>
            <div class="p-3 bg-primary-subtle text-primary rounded-3 fs-4">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white p-4 rounded-3 shadow-sm d-flex align-items-center justify-content-between border">
            <div>
                <span class="text-muted small fw-medium">Total Kendaraan</span>
                <h3 class="fw-bold mt-1 mb-0">{{ $totalKendaraan }}</h3>
            </div>
            <div class="p-3 bg-success-subtle text-success rounded-3 fs-4">
                <i class="fa-solid fa-car"></i>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white p-4 rounded-3 shadow-sm d-flex align-items-center justify-content-between border">
            <div>
                <span class="text-muted small fw-medium">Service Bulan Ini</span>
                <h3 class="fw-bold mt-1 mb-0">{{ $totalServiceBulanIni }}</h3>
            </div>
            <div class="p-3 bg-warning-subtle text-warning rounded-3 fs-4">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="bg-white p-4 rounded-3 shadow-sm d-flex align-items-center justify-content-between border">
            <div>
                <span class="text-muted small fw-medium">Total Pendapatan</span>
                <h3 class="fw-bold mt-1 mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-danger-subtle text-danger rounded-3 fs-4">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="bg-white p-4 rounded-3 shadow-sm border">
            <h5 class="fw-semibold text-dark mb-4">Grafik Service Bulanan</h5>
            
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="serviceChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('serviceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Unit Service',
                // Data bulan berjalan diambil dari controller Laravel dinamis
                data: [0, 0, 0, 0, 0, {{ $totalServiceBulanIni }}, 0, 0, 0, 0, 0, 0], 
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                fill: true,
                tension: 0.3,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Menjaga ukuran grafik tetap presisi sesuai pembungkus CSS
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5, // Lompatan interval angka di sumbu Y (0, 5, 10, dst)
                        font: { size: 11 }
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false } // Menghilangkan garis vertikal di latar belakang
                }
            }
        }
    });
</script>
@endpush