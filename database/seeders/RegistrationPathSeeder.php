<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegistrationPath;

class RegistrationPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paths = [
            [
                'name' => 'Reguler',
                'code' => 'REG',
                'description' => 'Jalur pendaftaran reguler',
                'is_active' => true
            ],
            [
                'name' => 'Prestasi',
                'code' => 'PRE',
                'description' => 'Jalur pendaftaran berdasarkan prestasi',
                'is_active' => true
            ],
            [
                'name' => 'Beasiswa KIP',
                'code' => 'KIP',
                'description' => 'Jalur pendaftaran beasiswa KIP',
                'is_active' => true
            ],
            [
                'name' => 'Transfer',
                'code' => 'TRF',
                'description' => 'Jalur pendaftaran mahasiswa transfer',
                'is_active' => true
            ]
        ];

        foreach ($paths as $path) {
            RegistrationPath::updateOrCreate(
                ['code' => $path['code']],
                $path
            );
        }
    }
}
