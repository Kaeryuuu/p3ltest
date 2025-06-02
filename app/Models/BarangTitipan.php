<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangTitipan extends Model
{
    protected $table = 'barangtitipan';
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode_barang', 'nama', 'harga', 'berat', 'status_barang',
        'tanggal_kadaluarsa', 'deskripsi', 'id_kategori', 'id_penitip',
        'perpanjangan', 'garansi', 'id_pembelian',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function transaksiPembelian()
    {
        return $this->hasMany(TransaksiPembelian::class, 'kode_barang', 'kode_barang');
    }
}