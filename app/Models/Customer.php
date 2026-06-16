<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // Mengizinkan semua field (nama, nomor hp, dll) disimpan sekaligus ke database
    protected $guarded = ['id']; 
}