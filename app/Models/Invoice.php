<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = ['id'];

    // Relasi: Satu invoice merujuk pada satu data pengerjaan service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}