<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // 1. Tampilkan Data
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    // 2. Simpan Pelanggan Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'nomor_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
        ]);

        Customer::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil didaftarkan!');
    }

    // 3. Update/Edit Data Pelanggan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'nomor_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // 4. Hapus Pelanggan
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus dari sistem.');
    }
}