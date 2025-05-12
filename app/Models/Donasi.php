<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    protected $table = 'donasi';
    protected $primaryKey = 'id_donasi';
    protected $fillable = ['id_request', 'tanggal_permintaan', 'status'];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    public function requestDonasi()
    {
        return $this->belongsTo(RequestDonasi::class, 'id_request', 'id_request');
    }
}