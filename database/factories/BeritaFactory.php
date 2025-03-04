<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Berita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 */
class BeritaFactory extends Factory
{
    protected $model = Berita::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        $admin = User::where('role', 'admin')->inRandomOrder()->first();

        return [
            'id_user' => $admin->id,
            'judul' => $faker->sentence(),
            'isi' => $faker->paragraph(5),
            'penulis' => $faker->name(),
            'tempat' => $faker->address(),
            'tanggal' => $faker->date(),
            'photo' => 'default-berita.png',
        ];
    }
}
