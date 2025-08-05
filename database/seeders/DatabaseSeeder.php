<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call specific seeders
        $this->call([
            AdminSeeder::class,
            ConfigurationSeeder::class,
            BankAccountSeeder::class,
            RegistrationPathSeeder::class,
            RegistrationWaveSeeder::class,
            KipQuotaSeeder::class,
        ]);
    }
}
