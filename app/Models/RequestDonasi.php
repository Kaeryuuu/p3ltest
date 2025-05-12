<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDonasi extends Model
{
    protected $table = 'requestdonasi';
    protected $primaryKey = 'id_request';
    protected $fillable = ['tanggal_permintaan', 'status'];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    public function donasi()
    {
        return $this->hasMany(Donasi::class, 'id_request', 'id_request');
    }
}