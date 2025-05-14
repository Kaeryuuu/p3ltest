<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    protected $table = 'transaksipembelian';
    protected $primaryKey = 'id_pembelian';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli', 'total', 'tanggal_pembelian', 'bukti_pembayaran',
        'tanggal_pengiriman', 'status', 'kode_barang', 'metode_pengiriman',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function barangTitipan()
    {
        return $this->belongsTo(BarangTitipan::class, 'kode_barang', 'kode_barang');
    }
}