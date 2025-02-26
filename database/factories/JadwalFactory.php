<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        $admin = User::where('role', 'admin')->inRandomOrder()->first();
        // Ambil waktu start antara 06:00 - 18:00
        $startHour = $faker->numberBetween(6, 18);
        $startMinute = $faker->randomElement(['00', '30']);
        $waktuStart = sprintf('%02d.%02d', $startHour, $startMinute);

        // Ambil waktu end, minimal 1-4 jam setelah waktu start
        $endHour = min($startHour + $faker->numberBetween(1, 4), 23);
        $endMinute = $faker->randomElement(['00', '30']);
        $waktuEnd = sprintf('%02d.%02d', $endHour, $endMinute);
        $kategoriList = ['pengecekan', 'sosialisasi', 'verifikasi', 'perbaikan'];
        return [
            'id_user' => $admin->id,
            'kategori' => $faker->randomElement($kategoriList),
            'judul' => $faker->sentence(3),
            'alamat' => $faker->address(),
            'waktu_start' => $waktuStart,
            'waktu_end' => $waktuEnd,
            'tanggal_start' => $faker->date(),
            'tanggal_end' => $faker->date(),
        ];
    }
}
