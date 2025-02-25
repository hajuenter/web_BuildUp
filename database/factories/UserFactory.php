<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory; // Tambahkan ini

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('id_ID'); // Gunakan locale Indonesia

        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'no_hp' => '62' . $faker->numerify('8##########'),
            'foto' => $faker->imageUrl(200, 200, 'people'),
            'alamat' => $faker->address(),
            'email_verified_at' => null,
        ];
    }

    public function admin(): static
    {
        return $this->state([
            'name' => 'Super Admin',
            'email' => 'eskuwut1945@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_hp' => '6289697082930',
            'foto' => $this->faker->imageUrl(200, 200, 'people'),
            'alamat' => 'Cangkringan Nganjuk',
            'email_verified_at' => now(),
        ]);
    }
    public function petugas(): static
    {
        return $this->state([
            'name' => 'Petugas Input CPB',
            'email' => 'esjeruk517@gmail.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'no_hp' => '6289697083456',
            'foto' => $this->faker->imageUrl(200, 200, 'people'),
            'alamat' => 'Cangkringan Nganjuk',
            'email_verified_at' => now(),
        ]);
    }
}
