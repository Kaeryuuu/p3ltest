<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penukaran extends Model
{
    protected $table = 'penukaran';
    protected $primaryKey = 'id_penukaran';
    protected $fillable = ['id_pembeli', 'kode_penukaran', 'tanggal_penukaran', 'id_merchandise'];

    protected $casts = [
        'tanggal_penukaran' => 'date',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'id_merchandise', 'id_merchandise');
    }
}