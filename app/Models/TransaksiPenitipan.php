<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransaksiPenitipan extends Model
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'transaksipenitipan'; //
    protected $primaryKey = 'id_penitipan'; //

    protected $fillable = [
        'id_penitip',
        'catatan',
        'tanggal_penitipan',
        'tanggal_konfirmasi_ambil',
        'tanggal_diambil',
        'id_pegawai',
        'no_nota'
    ]; //

    protected $casts = [
        'tanggal_penitipan' => 'date',
        'tanggal_konfirmasi_ambil' => 'date',
        'tanggal_diambil' => 'date',
    ]; //

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    /**
     * Get all consigned items associated with this consignment transaction.
     * A TransaksiPenitipan can have many BarangTitipan.
     * The foreign key 'id_penitipan' is in the 'barangtitipan' table.
     * IMPORTANT: This requires the UNIQUE KEY `fk_penitipan_barang` on `barangtitipan.id_penitipan`
     * in your SQL schema to be REMOVED for this relationship to be truly "hasMany".
     */
    public function barangTitipan()
    {
        return $this->hasMany(BarangTitipan::class, 'id_penitipan', 'id_penitipan');
    }
}