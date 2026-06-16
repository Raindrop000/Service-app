<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReminderController extends Controller
{
    public function index()
    {
        // Menyaring kendaraan yang service terakhirnya sudah lewat dari 6 bulan lalu
        $batasEnamBulan = Carbon::now()->subMonths(6)->toDateString();

        // Mengambil tanggal service paling terakhir untuk masing-masing kendaraan
        $subQuery = DB::table('services')
            ->select('vehicle_id', DB::raw('MAX(tanggal) as max_tgl'))
            ->groupBy('vehicle_id');

        $reminders = Service::joinSub($subQuery, 'latest_service', function ($join) {
                $join->on('services.vehicle_id', '=', 'latest_service.vehicle_id')
                     ->on('services.tanggal', '=', 'latest_service.max_tgl');
            })
            ->where('services.tanggal', '<=', $batasEnamBulan)
            ->with(['customer', 'vehicle'])
            ->get();

        return view('reminder.index', compact('reminders'));
    }
}