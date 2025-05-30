<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategoribarang';
    protected $primaryKey = 'id_kategori';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    public function barangTitipan()
    {
        return $this->hasMany(BarangTitipan::class, 'id_kategori', 'id_kategori');
    }

    public function subkategori()
    {
        return $this->hasMany(Subkategori::class, 'id_kategori', 'id_kategori');
    }
}