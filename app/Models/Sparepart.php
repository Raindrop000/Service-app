<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    // Mengizinkan semua field sparepart diisi lewat form modal
    protected $guarded = ['id']; 
}