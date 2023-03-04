<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$cSqURtekr48ONKZGezKPNe/eNezGSHvwNlYh87VsKgVPQYYAP4bay', // Dnsc1234
            'remember_token' => Str::random(10),
        ]);

        User::factory(10)->create();
    }
}
