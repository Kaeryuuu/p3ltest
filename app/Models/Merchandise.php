<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $table = 'merchandise';
    protected $primaryKey = 'id_merchandise';
    protected $fillable = ['nama', 'deskripsi', 'stok', 'url_gambar', 'poin_dibutuhkan'];

    public function penukaran()
    {
        return $this->hasMany(Penukaran::class, 'id_merchandise', 'id_merchandise');
    }
}