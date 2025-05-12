<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangTitipan extends Model
{
    protected $table = 'barangtitipan';
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'kode_barang', 'nama', 'harga', 'berat', 'status_barang', 'tanggal_kadaluarsa',
        'deskripsi', 'id_kategori', 'id_penitip', 'perpanjangan', 'garansi', 'id_pembelian'
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'garansi' => 'date',
        'perpanjangan' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    public function pembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function diskusi()
    {
        return $this->hasMany(Diskusi::class, 'kode_barang', 'kode_barang');
    }

    public function transaksiPenitipan()
    {
        return $this->hasOne(TransaksiPenitipan::class, 'kode_barang', 'kode_barang');
    }
}