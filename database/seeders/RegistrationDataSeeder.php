<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistrationWave;
use App\Models\RegistrationPath;
use App\Models\BankAccount;
use App\Models\Configuration;

class RegistrationDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Registration Paths
        $paths = [
            [
                'name' => 'Jalur Reguler',
                'code' => 'REG',
                'description' => 'Jalur pendaftaran reguler untuk mahasiswa umum',
                'is_active' => true,
            ],
            [
                'name' => 'Jalur Prestasi',
                'code' => 'PRES',
                'description' => 'Jalur pendaftaran berdasarkan prestasi akademik atau non-akademik',
                'is_active' => true,
            ],
            [
                'name' => 'Jalur KIP',
                'code' => 'KIP',
                'description' => 'Jalur pendaftaran untuk penerima beasiswa KIP',
                'is_active' => true,
            ],
        ];

        foreach ($paths as $path) {
            RegistrationPath::create($path);
        }

        // Create Registration Waves
        $waves = [
            [
                'name' => 'Gelombang 1 - 2025',
                'wave_number' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'administration_fee' => 500000,
                'registration_fee' => 1000000,
                'is_active' => true,
                'available_paths' => [
                    'reg' => true,
                    'pres' => true,
                    'kip' => true,
                ],
            ],
            [
                'name' => 'Gelombang 2 - 2025',
                'wave_number' => 2,
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(5),
                'administration_fee' => 550000,
                'registration_fee' => 1100000,
                'is_active' => true,
                'available_paths' => [
                    'reg' => true,
                    'pres' => true,
                    'kip' => false,
                ],
            ],
        ];

        foreach ($waves as $wave) {
            RegistrationWave::create($wave);
        }

        // Create Bank Accounts
        $bankAccounts = [
            [
                'bank_name' => 'Bank BRI',
                'account_number' => '1234567890123456',
                'account_holder' => 'STIKPAR SANTO THOMAS',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank BCA',
                'account_number' => '9876543210987654',
                'account_holder' => 'STIKPAR SANTO THOMAS',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '1111222233334444',
                'account_holder' => 'STIKPAR SANTO THOMAS',
                'is_active' => true,
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }

        // Create Basic Configurations
        $configurations = [
            'contact_email' => 'info@stikpar.ac.id',
            'contact_phone' => '021-12345678',
            'max_upload_size' => 5120, // 5MB in KB
            'allowed_file_types' => 'pdf,jpg,jpeg,png',
        ];

        foreach ($configurations as $key => $value) {
            Configuration::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
