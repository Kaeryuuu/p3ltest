<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    // Nama tabel di database
    protected $table = 'komisi';

    // Primary key tabel ini adalah 'kode_barang' dan bukan auto-incrementing
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    protected $keyType = 'string'; // Karena kode_barang kemungkinan adalah string

    // Tidak menggunakan timestamps created_at dan updated_at
    public $timestamps = false;

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'kode_barang',
        'id_pegawai', // Ini untuk hunter
        'id_penitip',
        'komisi_hunter',
        'komisi_mart',
        'komisi_penitip',
        'bonus',
    ];

    /**
     * Mendapatkan barang titipan yang terkait dengan komisi ini.
     * (Inverse of BarangTitipan->hasOne(Komisi::class))
     */
    public function barangTitipan()
    {
        return $this->belongsTo(BarangTitipan::class, 'kode_barang', 'kode_barang');
    }

    /**
     * Mendapatkan pegawai (hunter) yang terkait dengan komisi ini.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    /**
     * Mendapatkan penitip yang terkait dengan komisi ini.
     */
    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }
}