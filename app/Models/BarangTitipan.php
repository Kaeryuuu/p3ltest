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
        'kode_barang', 'nama', 'harga', 'berat', 'status', 'kondisi',
        'tanggal_kadaluarsa', 'deskripsi', 'id_kategori', 'id_penitip',
        'perpanjangan', 'garansi', 'id_pembelian','id_subkategori',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function subkategori()
    {
        return $this->belongsTo(SubKategori::class, 'id_subkategori');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function transaksiPembelian()
    {
        return $this->hasMany(TransaksiPembelian::class, 'kode_barang', 'kode_barang');
    }

    public function transaksiPenitipan()
    {
        return $this->belongsTo(TransaksiPenitipan::class, 'kode_barang', 'kode_barang');
    }

    public function fotos() // atau 'photos' jika lebih suka bahasa Inggris
    {
        return $this->hasMany(BarangTitipanFoto::class, 'kode_barang', 'kode_barang')->orderBy('urutan', 'asc');
    }

    public function getFotoUtamaAttribute()
    {
        return $this->fotos()->orderBy('urutan', 'asc')->first();
    }
}