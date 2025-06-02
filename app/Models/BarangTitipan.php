<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangTitipan extends Model
{
    protected $table = 'barangtitipan'; //
    protected $primaryKey = 'kode_barang'; //
    public $incrementing = false; //
    public $timestamps = false; //

    protected $fillable = [
        'kode_barang', 'nama', 'harga', 'berat', 'status', 'kondisi',
        'tanggal_kadaluarsa', 'deskripsi', 'id_kategori', 'id_penitip',
        'perpanjangan', 'garansi', 'id_pembelian', 'id_subkategori',
        'id_penitipan', 
        'terjual_cepat',
        'rating', 
    ]; //

    protected $casts = [
        'terjual_cepat' => 'boolean', //
        'perpanjangan' => 'boolean', //
        'harga' => 'decimal:2', 
        'berat' => 'decimal:2', 
        'tanggal_kadaluarsa' => 'date',
        'garansi' => 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }

    public function subkategori()
    {
        return $this->belongsTo(SubKategori::class, 'id_subkategori', 'id_subkategori');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    public function transaksiPembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function transaksiPenitipan()
    {
        return $this->belongsTo(TransaksiPenitipan::class, 'id_penitipan', 'id_penitipan');
    }

    public function fotos() 
    {
        return $this->hasMany(BarangTitipanFoto::class, 'kode_barang', 'kode_barang')->orderBy('urutan', 'asc');
    }

    public function getFotoUtamaAttribute()
    {
        return $this->fotos()->orderBy('urutan', 'asc')->first();
    }

    public function komisi()
    {
        return $this->hasOne(Komisi::class, 'kode_barang', 'kode_barang');
    }
}