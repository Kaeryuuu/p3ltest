<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penitip extends Authenticatable
{
    use Notifiable;

    protected $table = 'penitip';

    public $timestamps = false;

    protected $fillable = [
        'id_penitip',
        'nama',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

//     <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Penitip extends Model
// {
//     protected $table = 'penitip';
//     protected $primaryKey = 'id_penitip';
//     public $incrementing = false;
//     protected $keyType = 'string';
//     protected $fillable = ['nama', 'telepon', 'email', 'poin_loyalitas', 'password', 'url_foto'];

//     public function barangTitipan()
//     {
//         return $this->hasMany(BarangTitipan::class, 'id_penitip', 'id_penitip');
//     }

//     public function transaksiPenitipan()
//     {
//         return $this->hasMany(TransaksiPenitipan::class, 'id_penitip', 'id_penitip');
//     }
// }
}
