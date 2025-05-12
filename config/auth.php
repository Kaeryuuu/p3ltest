<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'pembeli' => [
            'driver' => 'session',
            'provider' => 'pembeli',
        ],
        'penitip' => [
            'driver' => 'session',
            'provider' => 'penitip',
        ],
        'organisasi' => [
            'driver' => 'session',
            'provider' => 'organisasi',
        ],
        'pegawai' => [
            'driver' => 'session',
            'provider' => 'pegawai',
        ],
        'sanctum' => [
        'driver' => 'sanctum',
        'provider' => null,
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'pembeli' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pembeli::class,
        ],
        'penitip' => [
            'driver' => 'eloquent',
            'model' => App\Models\Penitip::class,
        ],
        'organisasi' => [
            'driver' => 'eloquent',
            'model' => App\Models\Organisasi::class,
        ],
        'pegawai' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pegawai::class,
        ],
    ],
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => 10800,
];