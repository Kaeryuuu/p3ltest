<?php

namespace Database\Seeders;

use App\Models\Penitip;
use Illuminate\Database\Seeder;

class PenitipKtpSeeder extends Seeder
{
    public function run(): void
    {
        $ktpNumbers = [
            'T1' => '1234567890123456',
            'T2' => '2234567890123456',
            'T3' => '3234567890123456',
            'T4' => '4234567890123456',
            'T5' => '5234567890123456',
            'T6' => '6234567890123456',
            'T7' => '7234567890123456',
            'T8' => '8234567890123456',
            'T9' => '9234567890123456',
            'T10' => '1034567890123456',
            'T11' => '1134567890123456',
        ];

        foreach ($ktpNumbers as $id => $no_ktp) {
            Penitip::where('id_penitip', $id)->update(['no_ktp' => $no_ktp]);
        }
    }
}