<?php

namespace Database\Seeders;

use App\Models\Data_CPB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Data_CPBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 5; $i++) {
            Data_CPB::create([
                'nama'          => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'umur'          => $faker->numberBetween(18, 80), // Umur antara 18 - 80 tahun
                'nik'           => $faker->unique()->numerify('################'), // 16 digit angka
                'no_kk'         => $faker->unique()->numerify('################'), // 16 digit angka
                'alamat'        => $faker->address
            ]);
        }
    }
}
