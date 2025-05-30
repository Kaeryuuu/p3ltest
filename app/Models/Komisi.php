<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    protected $table = 'komisi';
    protected $primaryKey = 'id_komisi';
    protected $fillable = ['bonus_hunter', 'bonus_mart', 'id_pembelian', 'bonus_penitip'];

    public function pembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'id_pembelian', 'id_pembelian');
    }
}