<?php

namespace Database\Seeders;

use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PasswordHashSeeder extends Seeder
{
    public function run()
    {
        Pembeli::all()->each(function ($pembeli) {
            $pembeli->update(['password' => Hash::make($pembeli->password)]);
        });

        Penitip::all()->each(function ($penitip) {
            $penitip->update(['password' => Hash::make($penitip->password)]);
        });

        Organisasi::all()->each(function ($organisasi) {
            $organisasi->update(['password' => Hash::make($organisasi->password)]);
        });

        Pegawai::all()->each(function ($pegawai) {
            $pegawai->update(['password' => Hash::make($pegawai->password)]);
        });
    }
}