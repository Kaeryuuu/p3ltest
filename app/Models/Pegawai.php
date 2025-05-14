<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_jabatan', 'nama', 'email', 'password', 'status'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function transaksiPenitipan()
    {
        return $this->hasMany(TransaksiPenitipan::class, 'id_pegawai', 'id_pegawai');
    }
}