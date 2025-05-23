<?php

namespace Database\Factories;

use App\Models\DataCPB;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'nama'       => strtoupper($faker->name()),
            'alamat'     => $faker->streetAddress . '; ' . $faker->city . '; ' . $faker->state,
            'nik'        => $nik,
            'no_kk'      => $no_kk,
            'pekerjaan'  => $faker->jobTitle(),
            'email'      => $faker->unique()->safeEmail(),
            'foto_rumah' => 'up/data_cpb/default-cpb.png',
            'koordinat' => $faker->randomFloat(14, -6.4, -5.9) . ', ' . $faker->randomFloat(14, 106.6, 107.0),
            'status'     => 'Tidak Terverifikasi',
            'pengecekan' => 'Belum Dicek',
        ];
    }
}
