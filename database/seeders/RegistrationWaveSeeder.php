<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegistrationWave;

class RegistrationWaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $waves = [
            [
                'name' => 'Gelombang 1',
                'wave_number' => 1,
                'start_date' => '2025-01-01',
                'end_date' => '2025-03-31',
                'administration_fee' => 200000,
                'registration_fee' => 2500000,
                'is_active' => true
            ],
            [
                'name' => 'Gelombang 2',
                'wave_number' => 2,
                'start_date' => '2025-04-01',
                'end_date' => '2025-06-30',
                'administration_fee' => 250000,
                'registration_fee' => 2500000,
                'is_active' => true
            ],
            [
                'name' => 'Gelombang 3',
                'wave_number' => 3,
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'administration_fee' => 300000,
                'registration_fee' => 2500000,
                'is_active' => true
            ]
        ];

        foreach ($waves as $wave) {
            RegistrationWave::create($wave);
        }
    }
}
