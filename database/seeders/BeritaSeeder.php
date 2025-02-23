<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 10 berita menggunakan factory
        Berita::factory()->count(10)->create();
    }
}
