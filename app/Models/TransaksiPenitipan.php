<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenitipan extends Model
{
    protected $table = 'transaksipenitipan';
    protected $primaryKey = 'id_penitipan';
    protected $fillable = ['id_penitip', 'kode_barang', 'catatan', 'tanggal_penitipan', 'id_pegawai'];

    protected $casts = [
        'tanggal_penitipan' => 'date',
    ];

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    public function barang()
    {
        return $this->belongsTo(BarangTitipan::class, 'kode_barang', 'kode_barang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}