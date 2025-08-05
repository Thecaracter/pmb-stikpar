<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin PMB',
            'email' => 'admin@gmail.com',
            'phone' => '+62812345678',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create test user/mahasiswa
        User::create([
            'name' => 'Rizqi',
            'email' => 'rizqi@gmail.com',
            'phone' => '+62812345679',
            'role' => 'user',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
