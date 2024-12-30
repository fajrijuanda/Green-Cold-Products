<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Seeder for Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin1@mail.com'], // Condition to avoid duplication
            [
                'first_name' => 'Admin',
                'last_name' => '1',
                'slug' => Str::slug('Admin 1'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'password' => Hash::make('admin123'),
                'phone_number' => '+6285217861296',
                'address' => 'Perum Karaba Indah Blok D No. 24',
                'province' => 'Jawa Barat',
                'country' => 'Indonesia',
                'zip_code' => '41361',
                'project' => 'LKI',
                'avatar' => '1.png',
                'is_active' => true,
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists.');
        }

        // Seeder for User
        $user = User::firstOrCreate(
            ['email' => 'admin2@mail.com'], // Condition to avoid duplication
            [
                'first_name' => 'Admin',
                'last_name' => '2',
                'slug' => Str::slug('Admin 2'),
                'email_verified_at' => now(),
                'role' => 'user',
                'password' => Hash::make('admin123'),
                'phone_number' => '+6281234567890',
                'address' => 'Jl. Mawar No. 45',
                'province' => 'Jawa Tengah',
                'country' => 'Indonesia',
                'zip_code' => '40256',
                'project' => 'MRT',
                'avatar' => '2.png',
                'is_active' => true,
            ]
        );

        if ($user->wasRecentlyCreated) {
            $this->command->info('Standard user created successfully.');
        } else {
            $this->command->info('Standard user already exists.');
        }
    }
}
