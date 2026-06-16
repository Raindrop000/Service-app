<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // 1. Tampilkan Halaman & Data
    public function index()
    {
        // Mengambil data kendaraan beserta data pemiliknya (customer)
        $vehicles = Vehicle::with('customer')->latest()->get();
        // Mengambil semua data pelanggan untuk pilihan dropdown di Form Modal
        $customers = Customer::orderBy('nama_pelanggan', 'asc')->get();

        return view('vehicles.index', compact('vehicles', 'customers'));
    }

    // 2. Simpan Kendaraan Baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'nomor_polisi' => 'required|string|max:20|unique:vehicles,nomor_polisi',
            'merk_mobil' => 'required|string|max:50',
            'tipe_mobil' => 'required|string|max:50',
            'tahun' => 'nullable|integer',
            'nomor_rangka' => 'nullable|string|max:100',
            'nomor_mesin' => 'nullable|string|max:100',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan baru berhasil didaftarkan!');
    }

    // 3. Update Data Kendaraan
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'nomor_polisi' => 'required|string|max:20|unique:vehicles,nomor_polisi,'.$id,
            'merk_mobil' => 'required|string|max:50',
            'tipe_mobil' => 'required|string|max:50',
            'tahun' => 'nullable|integer',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    // 4. Hapus Kendaraan
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil dihapus dari sistem.');
    }
}