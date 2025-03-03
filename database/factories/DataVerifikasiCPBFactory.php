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
        $faker = FakerFactory::create('id_ID');

        $bobot_tembok = [12.4, 13.65, 5.675, 5.675, 16.1, 2.81, 3.02, 6.3, 3.4, 10.52, 13.1, 3.91, 2.01, 1.43];
        $bobot_kayu = [15.19, 3.39, 5.825, 5.825, 29.58, 4.6, 1.08, 1.09, 0.71, 4.49, 3.93, 19.05, 1.42, 3.82];

        $tipe = $faker->randomElement(['T', 'K']);
        $bobot = $tipe === 'T' ? $bobot_tembok : $bobot_kayu;

        // Nilai kerusakan (0, 0.25, 0.5, 0.75, 1)
        $kerusakan = array_map(fn() => $faker->randomElement([0, 0.25, 0.5, 0.75, 1]), range(1, 14));

        $kesanggupan_berswadaya = $faker->boolean() ? 1 : 0;

        // Hitung total penilaian kerusakan
        $penilaian_kerusakan = 0;
        for ($i = 0; $i < count($kerusakan); $i++) {
            $penilaian_kerusakan += $kerusakan[$i] * $bobot[$i];
        }
        $penilaian_kerusakan *= $kesanggupan_berswadaya;

        if ($penilaian_kerusakan > 66) {
            $nilai_bantuan = 20000000;
            $catatan = "Rusak Berat";
        } elseif ($penilaian_kerusakan > 46) {
            $nilai_bantuan = 10000000;
            $catatan = "Rusak Sedang";
        } elseif ($penilaian_kerusakan > 30) {
            $nilai_bantuan = 5000000;
            $catatan = "Rusak Ringan";
        } else {
            $nilai_bantuan = 0;
            $catatan = "Tidak mendapat bantuan";
        }

        return [
            'penutup_atap'       => $kerusakan[0],
            'rangka_atap'        => $kerusakan[1],
            'kolom'              => $kerusakan[2],
            'ring_balok'         => $kerusakan[3],
            'dinding_pengisi'    => $kerusakan[4],
            'kusen'              => $kerusakan[5],
            'pintu'              => $kerusakan[6],
            'jendela'            => $kerusakan[7],
            'struktur_bawah'     => $kerusakan[8],
            'penutup_lantai'     => $kerusakan[9],
            'pondasi'            => $kerusakan[10],
            'sloof'              => $kerusakan[11],
            'mck'                => $kerusakan[12],
            'air_kotor'          => $kerusakan[13],
            'kesanggupan_berswadaya' => $kesanggupan_berswadaya,
            'tipe'               => $tipe,
            'penilaian_kerusakan' => $penilaian_kerusakan,
            'nilai_bantuan'       => $nilai_bantuan,
            'catatan'             => $catatan,
        ];
    }
}
