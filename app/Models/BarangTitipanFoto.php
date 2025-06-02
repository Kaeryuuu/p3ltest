<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTitipanFoto extends Model
{
    use HasFactory;

    protected $table = 'barang_titipan_fotos';

    protected $fillable = [
        'kode_barang',
        'url_foto',
        'urutan',
    ];

    public function barangTitipan()
    {
        return $this->belongsTo(BarangTitipan::class, 'kode_barang', 'kode_barang');
    }
}