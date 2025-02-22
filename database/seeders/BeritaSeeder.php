<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $users = User::where('role', 'admin')->get();

        // Jika tidak ada user, hentikan proses seeder
        if ($users->isEmpty()) {
            return;
        }

        // Buat 5 berita untuk user yang ada
        for ($i = 0; $i < 10; $i++) {
            Berita::create([
                'id_user' => $users->random()->id, // Pilih user secara acak dari yang ada
                'judul' => $faker->sentence(),
                'isi' => $faker->paragraph(5),
                'penulis' => $faker->name(),
                'tanggal' => $faker->date(),
                'photo' => 'up/berita/' . $faker->image('public/up/berita', 640, 480, null, false),
            ]);
        }
    }
}
