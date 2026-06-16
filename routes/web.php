<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReminderController;

// Pastikan ada ->name('dashboard')
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ISI UTAMA: Pastikan baris ini tertulis persis seperti ini dan DI-SAVE
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/spareparts', [SparepartController::class, 'index'])->name('spareparts.index');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');

// Menu Pelanggan (Lengkap CRUD)
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/update/{id}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

// Menu Kendaraan (Lengkap CRUD)
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('vehicles.store');
Route::put('/vehicles/update/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
Route::delete('/vehicles/destroy/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

// Menu Data Service (Lengkap CRUD)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::post('/services/store', [ServiceController::class, 'store'])->name('services.store');
Route::put('/services/update/{id}', [ServiceController::class, 'update'])->name('services.update');
Route::patch('/services/update-status/{id}', [ServiceController::class, 'updateStatus'])->name('services.updateStatus');
Route::delete('/services/destroy/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

// Menu Spareparts (Lengkap CRUD)
Route::get('/spareparts', [SparepartController::class, 'index'])->name('spareparts.index');
Route::post('/spareparts/store', [SparepartController::class, 'store'])->name('spareparts.store');
Route::put('/spareparts/update/{id}', [SparepartController::class, 'update'])->name('spareparts.update');
Route::delete('/spareparts/destroy/{id}', [SparepartController::class, 'destroy'])->name('spareparts.destroy');

// Menu Invoices (Kwitansi & Pembayaran)
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::post('/invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/print/{id}', [InvoiceController::class, 'print'])->name('invoices.print');
Route::delete('/invoices/destroy/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

// Menu Reminder Service (WhatsApp Otomatis)
Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');