<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Organisasi extends Authenticatable
{
    use Notifiable;
    protected $table = 'organisasi';
    protected $primaryKey = 'id_organisasi';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['id_organisasi', 'nama', 'deskripsi', 'email', 'password'];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}


