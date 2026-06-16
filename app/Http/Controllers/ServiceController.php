<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    // 1. Tampilkan Halaman Antrean Service
    public function index()
    {
        $services = Service::with(['customer', 'vehicle'])->latest()->get();
        $customers = Customer::orderBy('nama_pelanggan', 'asc')->get();
        $vehicles = Vehicle::orderBy('nomor_polisi', 'asc')->get();
        
        // Mengambil user dengan role mekanik atau user pertama sebagai mekanik default
        $mechanics = User::all(); 

        return view('services.index', compact('services', 'customers', 'vehicles', 'mechanics'));
    }

    // 2. Simpan Perintah Kerja (Work Order) Baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'keluhan' => 'required|string',
        ]);

        // AUTO-GENERATION: Membuat nomor urut service otomatis (Contoh: WO-20260616-0001)
        $hariIni = Carbon::now()->format('Ymd');
        $jumlahServiceHariIni = Service::whereDate('tanggal', Carbon::today())->count();
        $nomorUrut = str_pad($jumlahServiceHariIni + 1, 4, '0', STR_PAD_LEFT);
        $nomorService = "WO-" . $hariIni . "-" . $nomorUrut;

        Service::create([
            'nomor_service' => $nomorService,
            'tanggal' => Carbon::today()->toDateString(),
            'customer_id' => $request->customer_id,
            'vehicle_id' => $request->vehicle_id,
            'keluhan' => $request->keluhan,
            'diagnosa' => $request->diagnosa,
            'pekerjaan_dilakukan' => $request->pekerjaan_dilakukan,
            'mekanik_id' => $request->mekanik_id ?? 1, // Default ke user ID 1 jika belum ada sistem login khusus
            'status' => 'Proses'
        ]);

        return redirect()->route('services.index')->with('success', 'Work Order Service berhasil dibuat!');
    }

    // 3. Update Detail Pengerjaan & Diagnosa Mekanik
    public function update(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'nullable|string',
            'pekerjaan_dilakukan' => 'nullable|string',
            'status' => 'required|in:Proses,Selesai',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'diagnosa' => $request->diagnosa,
            'pekerjaan_dilakukan' => $request->pekerjaan_dilakukan,
            'status' => $request->status,
        ]);

        return redirect()->route('services.index')->with('success', 'Detail service berhasil diperbarui!');
    }

    // 4. Hapus Data Service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Data service berhasil dihapus.');
    }
}