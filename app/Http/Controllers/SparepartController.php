<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    // 1. Tampilkan Data Stok Gudang
    public function index()
    {
        $spareparts = Sparepart::latest()->get();
        return view('sparepart.index', compact('spareparts'));
    }

    // 2. Simpan Suku Cadang Baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_sparepart' => 'required|string|max:50|unique:spareparts,kode_sparepart',
            'nama_sparepart' => 'required|string|max:150',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        Sparepart::create($request->all());

        return redirect()->route('spareparts.index')->with('success', 'Sparepart baru berhasil ditambahkan ke gudang!');
    }

    // 3. Update Informasi & Stok Barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_sparepart' => 'required|string|max:50|unique:spareparts,kode_sparepart,'.$id,
            'nama_sparepart' => 'required|string|max:150',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update($request->all());

        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil diperbarui!');
    }

    // 4. Hapus Barang dari Gudang
    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil dihapus dari sistem.');
    }
}