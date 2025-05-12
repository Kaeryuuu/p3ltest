<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopSeller extends Model
{
    protected $table = 'topseller';
    protected $primaryKey = 'id_seller';
    protected $fillable = ['tanggal_mulai', 'tanggal_selesai'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}