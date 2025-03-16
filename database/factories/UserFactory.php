<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory; // Tambahkan ini
use Illuminate\Database\Eloquent\Factories\Factory;

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

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            ApiKey::create([
                'user_id' => $user->id,
                'api_key' => hash('sha256', Str::random(64)),
            ]);
        });
    }

    public function admin(): static
    {
        return $this->state([
            'name' => 'Super Admin',
            'email' => 'eskuwut1945@gmail.com',
            'password' => Hash::make('Admin123#'),
            'role' => 'admin',
            'no_hp' => '6289697082930',
            'foto' => 'default-profile.png',
            'alamat' => 'Cangkringan Nganjuk',
            'email_verified_at' => now(),
        ]);
    }
    public function petugas(): static
    {
        return $this->state([
            'name' => 'Petugas Input CPB',
            'email' => 'esjeruk517@gmail.com',
            'password' => Hash::make('Petugas123#'),
            'role' => 'petugas',
            'no_hp' => '6289697083456',
            'foto' => 'default-profile.png',
            'alamat' => 'Cangkringan Nganjuk',
            'email_verified_at' => now(),
        ]);
    }
    public function user(): static
    {
        return $this->state([
            'name' => 'Petugas Verifikasi CPB',
            'email' => 'escincau42@gmail.com',
            'password' => Hash::make('User123#'),
            'role' => 'user',
            'no_hp' => '6289697083111',
            'foto' => 'default-profile.png',
            'alamat' => 'Cangkringan Nganjuk',
            'email_verified_at' => now(),
        ]);
    }
}
