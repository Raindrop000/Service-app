<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan', 100);
            $table->string('nomor_hp', 20);
            $table->text('alamat')->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
        });

        // 2. Tabel Vehicles
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('nomor_polisi', 15)->unique();
            $table->string('merk_mobil', 50);
            $table->string('tipe_mobil', 50);
            $table->year('tahun');
            $table->string('nomor_rangka', 50)->nullable();
            $table->string('nomor_mesin', 50)->nullable();
            $table->string('warna', 30)->nullable();
            $table->timestamps();
        });

        // 3. Tabel Spareparts
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sparepart', 20)->unique();
            $table->string('nama_sparepart', 100);
            $table->integer('stok')->default(0);
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('harga_jual', 12, 2);
            $table->timestamps();
        });

        // 4. Tabel Services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_service', 20)->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->text('keluhan');
            $table->text('diagnosa')->nullable();
            $table->text('pekerjaan_dilakukan')->nullable();
            $table->foreignId('mekanik_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Proses', 'Selesai'])->default('Proses');
            $table->timestamps();
        });

        // 5. Tabel Service Details (Jasa)
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('nama_jasa', 150);
            $table->decimal('biaya', 12, 2);
            $table->timestamps();
        });

        // 6. Tabel Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice', 20)->unique();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->decimal('total_biaya_jasa', 12, 2)->default(0);
            $table->decimal('total_biaya_sparepart', 12, 2)->default(0);
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('pajak', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->timestamps();
        });

        // 7. Tabel Invoice Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            $table->integer('qty')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('service_details');
        Schema::dropIfExists('services');
        Schema::dropIfExists('spareparts');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
    }
};