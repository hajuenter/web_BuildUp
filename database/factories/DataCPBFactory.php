<?php

namespace Database\Factories;

use App\Models\DataCPB;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory; // Tambahkan ini

class DataCPBFactory extends Factory
{
    protected $model = DataCPB::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('id_ID'); // Pastikan pakai locale Indonesia

        static $niks = [];
        static $noKks = [];

        do {
            $nik = $faker->numerify('################'); // 16 digit angka
        } while (in_array($nik, $niks) || DataCPB::where('nik', $nik)->exists());
        $niks[] = $nik;

        do {
            $no_kk = $faker->numerify('################'); // 16 digit angka
        } while (in_array($no_kk, $noKks) || DataCPB::where('no_kk', $no_kk)->exists());
        $noKks[] = $no_kk;

        return [
            'nama'          => $faker->name(), // Nama dalam bahasa Indonesia
            'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
            'umur'          => $faker->numberBetween(18, 80),
            'nik'           => $nik,
            'no_kk'         => $no_kk,
            'alamat'        => $faker->address(), // Alamat dalam bahasa Indonesia
        ];
    }
}
