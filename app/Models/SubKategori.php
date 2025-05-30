<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkategori extends Model
{
    protected $table = 'subkategori';
    protected $primaryKey = 'id_subkategori';
    protected $fillable = ['id_kategori', 'namaSubKategori'];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }
}