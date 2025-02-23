<?php

namespace Database\Factories;

use App\Models\DataVerifikasiCPB;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory; // Tambahkan ini

class DataVerifikasiCPBFactory extends Factory
{
    protected $model = DataVerifikasiCPB::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('id_ID'); // Pastikan menggunakan locale Indonesia

        return [
            // 'nik' tidak diisi di sini, karena akan diisi oleh seeder
            'penutup_atap'       => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'rangka_atap'        => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'kolom'              => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'ring_balok'         => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'dinding_pengisi'    => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'kusen'              => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'pintu'              => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'jendela'            => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'struktur_bawah'     => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'penutup_lantai'     => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'pondasi'            => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'sloof'              => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'mck'                => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'air_kotor'          => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]),
            'kesanggupan_berswadaya' => $faker->boolean(),
            'tipe'               => $faker->randomElement(['T', 'K']),
        ];
    }
}
