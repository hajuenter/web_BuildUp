<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 admin
        User::factory()->admin()->create();

        // Buat 5 user biasa
        User::factory()->count(5)->create();
    }
}
