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
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout-confirmation' => [
        'fields' => [
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password',
        ],
    ],
];