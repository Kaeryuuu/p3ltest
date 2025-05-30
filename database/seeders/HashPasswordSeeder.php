<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashPasswordSeeder extends Seeder
{
    public function run()
    {
        $pegawais = DB::table('pegawai')->get();
        $count = 0;

        foreach ($pegawais as $pegawai) {
            if (strpos($pegawai->password, '$2y$') === 0) continue;

            DB::table('pegawai')
                ->where('id_pegawai', $pegawai->id_pegawai)
                ->update(['password' => Hash::make($pegawai->password)]);

            $count++;
        }

        echo "Selesai. $count password berhasil di-hash.\n";
    }
}
