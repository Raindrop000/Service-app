<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    // 1. Tampilkan Semua Riwayat Nota/Kwitansi
    public function index()
    {
        $invoices = Invoice::with('service.customer', 'service.vehicle')->latest()->get();
        
        // Hanya mengambil data service yang berstatus 'Selesai' dan belum dibuatkan invoice-nya
        $completedServices = Service::where('status', 'Selesai')
            ->whereNotExists(function ($query) {
                $query->select('id')->from('invoices')->whereRaw('invoices.service_id = services.id');
            })->get();

        return view('invoices.index', compact('invoices', 'completedServices'));
    }

    // 2. Simpan & Hitung otomatis Transaksi Pembayaran Baru
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id|unique:invoices,service_id',
            'total_biaya_jasa' => 'required|numeric|min:0',
            'total_biaya_sparepart' => 'required|numeric|min:0',
        ]);

        // Pembuatan Nomor Nota Otomatis (Contoh: INV-20260616-0001)
        $hariIni = Carbon::now()->format('Ymd');
        $jumlahInvoiceHariIni = Invoice::whereDate('created_at', Carbon::today())->count();
        $nomorUrut = str_pad($jumlahInvoiceHariIni + 1, 4, '0', STR_PAD_LEFT);
        $nomorInvoice = "INV-" . $hariIni . "-" . $nomorUrut;

        // Hitung Grand Total Otomatis di server latar belakang
        $grandTotal = $request->total_biaya_jasa + $request->total_biaya_sparepart;

        Invoice::create([
            'nomor_invoice' => $nomorInvoice,
            'service_id' => $request->service_id,
            'total_biaya_jasa' => $request->total_biaya_jasa,
            'total_biaya_sparepart' => $request->total_biaya_sparepart,
            'grand_total' => $grandTotal,
            'status_pembayaran' => 'Lunas'
        ]);

        return redirect()->route('invoices.index')->with('success', 'Kwitansi pembayaran / Invoice berhasil diterbitkan!');
    }

    // 3. Menghapus Nota Keuangan
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Data invoice berhasil dihapus dari pembukuan.');
    }
}