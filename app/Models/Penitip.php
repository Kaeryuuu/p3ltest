<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;

class Penitip extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'penitip';
    protected $primaryKey = 'id_penitip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penitip',
        'no_ktp',
        'nama',
        'telepon',
        'email',
        'poin_loyalitas',
        'password',
        'url_foto',
        'status',
        'saldo',
        'jumlah_jual',
        'rating',
        'badge',
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'poin_loyalitas' => 0,
        'status' => 'active',
        'saldo' => 0,
        'jumlah_jual' => 0,
        'rating' => 0,
        'badge' => null,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!empty($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });

        static::updating(function ($model) {
            if (!empty($model->password) && $model->isDirty('password')) {
                $model->password = Hash::make($model->password);
            }
        });
    }

    public function barangTitipan()
    {
        return $this->hasMany(BarangTitipan::class, 'id_penitip', 'id_penitip');
    }
}