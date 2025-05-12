<?php

namespace Database\Seeders;

use App\Models\Pembeli;
use Illuminate\Database\Seeder;

class PembeliPoinLoyalitasSeeder extends Seeder
{
    public function run(): void
    {
        $pembeliData = [
            '1' => 100,
            '2' => 50,
            '3' => 200,
            '4' => 0,
            '5' => 150,
            '6' => 75,
            '7' => 300,
            '8' => 25,
            '9' => 120,
            '10' => 80,
        ];

        foreach ($pembeliData as $id => $poin) {
            Pembeli::where('id_pembeli', $id)->update(['poin_loyalitas' => $poin]);
        }
    }
}
