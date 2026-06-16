<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    // Buka kunci proteksi form
    protected $guarded = ['id'];

    // Relasi: Setiap kendaraan dimiliki oleh satu pelanggan
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}