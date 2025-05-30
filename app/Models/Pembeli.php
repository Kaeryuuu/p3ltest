<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pembeli extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;

    protected $table = 'pembeli';
    public $incrementing = true;
    protected $primaryKey = 'id_pembeli';
    protected $fillable = [
        'nama', 'telepon', 'email', 'poin_loyalitas', 'password', 'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'poin_loyalitas' => 0,
        'status' => 'active',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

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