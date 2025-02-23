<?php

namespace Database\Seeders;

use App\Models\DataCPB;
use Illuminate\Database\Seeder;

class DataCPBSeeder extends Seeder
{
    public function run(): void
    {
        DataCPB::factory()->count(10)->create();
    }
}
