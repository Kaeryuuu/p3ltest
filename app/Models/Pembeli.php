<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    public $timestamps = false;

    protected $table = 'pembeli';
    public $incrementing = false;
    protected $primaryKey = 'id_pembeli';
    protected $fillable = ['id_pembeli', 'nama', 'telepon', 'email', 'password'];

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pembeli', 'id_pembeli');
    }

    public function diskusi()
    {
        return $this->hasMany(Diskusi::class, 'id_pembeli', 'id_pembeli');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'id_pembeli', 'id_pembeli');
    }

    public function penukaran()
    {
        return $this->hasMany(Penukaran::class, 'id_pembeli', 'id_pembeli');
    }

    public function transaksiPembelian()
    {
        return $this->hasMany(TransaksiPembelian::class, 'id_pembeli', 'id_pembeli');
    }
}
