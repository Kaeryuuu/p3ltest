<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    protected $table = 'diskusi';
    protected $primaryKey = 'id_diskusi';
    protected $fillable = ['tanggal_dibuat', 'isi', 'id_pembeli', 'kode_barang'];

    protected $casts = [
        'tanggal_dibuat' => 'date',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    public function barang()
    {
        return $this->belongsTo(BarangTitipan::class, 'kode_barang', 'kode_barang');
    }
}