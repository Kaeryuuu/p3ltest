<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    // use HasFactory;

    protected $table = 'transaksipembelian';
    protected $primaryKey = 'id_pembelian';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli',
        'id_kurir',
        'total',
        'ongkir',
        'poin_diskon',
        'total_akhir',
        'poin_diperoleh',
        'tanggal_pembayaran',
        'tanggal_pembelian',
        'bukti_pembayaran',
        'tanggal_pengiriman',
        'tanggal_pengambilan',
        'status',
        'metode_pengiriman',
        'alamat',
        'no_nota_pembelian',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'ongkir' => 'integer',
        'poin_diskon' => 'integer',
        'total_akhir' => 'integer',
        'poin_diperoleh' => 'integer',
        'tanggal_pembayaran' => 'datetime',
        'tanggal_pembelian' => 'date',
        'tanggal_pengiriman' => 'date',
        'tanggal_pengambilan' => 'datetime',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }

    public function kurir()
    {
        return $this->belongsTo(Pegawai::class, 'id_kurir', 'id_pegawai');
    }

    /**
     * Get all consigned items associated with this purchase transaction.
     * A TransaksiPembelian can have many BarangTitipan.
     * The foreign key 'id_pembelian' is in the 'barangtitipan' table.
     */
    public function barangTitipan()
    {
        return $this->hasMany(BarangTitipan::class, 'id_pembelian', 'id_pembelian');
    }

    // Komisi relationship removed as it's better accessed via BarangTitipan items.
}