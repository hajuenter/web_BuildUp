<?php

namespace Database\Seeders;

use App\Models\Data_CPB;
use App\Models\Data_Verifikasi_CPB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Data_Verifikasi_CPBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        // Ambil semua NIK dari tabel data_cpb
        $niks = Data_CPB::pluck('nik')->toArray();

        // Cek apakah ada data CPB yang tersedia
        if (empty($niks)) {
            return; // Jangan insert jika tidak ada NIK yang tersedia
        }
        for ($i = 0; $i < 5; $i++) {
            Data_Verifikasi_CPB::create([
                'nik' => $niks[$i],
                'penutup_atap' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'rangka_atap' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'kolom' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'ring_balok' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'dinding_pengisi' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'kusen' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'pintu' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'jendela' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'struktur_bawah' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'penutup_lantai' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'pondasi' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'sloof' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'mck' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'air_kotor' => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
                'kesanggupan_berswadaya' => $faker->boolean(),
                'tipe' => $faker->randomElement(['T', 'K']),
            ]);
        }
    }
}
