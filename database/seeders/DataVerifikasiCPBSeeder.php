<?php

namespace Database\Seeders;

use App\Models\DataVerifikasiCPB;
use App\Models\DataCPB;
use Illuminate\Database\Seeder;

class DataVerifikasiCPBSeeder extends Seeder
{
    public function run(): void
    {
        $niks = DataCPB::pluck('nik')->toArray(); 

        foreach ($niks as $nik) {
            DataVerifikasiCPB::factory()->create([
                'nik' => $nik,
            ]);
        }
    }
}
