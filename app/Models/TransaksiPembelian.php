<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    protected $table = 'transaksipembelian';
    protected $primaryKey = 'id_pembelian';
    protected $fillable = [
        'id_pembeli', 'total', 'tanggal_pembelian', 'bukti_pembayaran',
        'tanggal_pengiriman', 'status', 'kode_barang', 'metode_pengiriman'
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'tanggal_pengiriman' => 'date',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    public function barang()
    {
        return $this->belongsTo(BarangTitipan::class, 'kode_barang', 'kode_barang');
    }

    public function komisi()
    {
        return $this->hasMany(Komisi::class, 'id_pembelian', 'id_pembelian');
    }

    public function barangTitipan()
    {
        return $this->hasMany(BarangTitipan::class, 'id_pembelian', 'id_pembelian');
    }
}