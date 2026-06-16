<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Mengambil data statistik untuk Card Utama
        $totalPelanggan = Customer::count();
        $totalKendaraan = Vehicle::count();
        
        // Menghitung service khusus bulan berjalan saat ini
        $totalServiceBulanIni = Service::whereMonth('tanggal', Carbon::now()->month)
                                       ->whereYear('tanggal', Carbon::now()->year)
                                       ->count();
                                       
        // Menghitung total seluruh pendapatan bengkel dari grand_total invoice
        $totalPendapatan = Invoice::sum('grand_total');

        // 2. Mengirim data di atas ke view dashboard index
        return view('dashboard.index', compact(
            'totalPelanggan', 
            'totalKendaraan', 
            'totalServiceBulanIni', 
            'totalPendapatan'
        ));
    }
}