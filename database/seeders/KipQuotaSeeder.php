<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KipQuota;

class KipQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kipQuotas = [
            [
                'year' => 2024,
                'total_quota' => 100,
                'remaining_quota' => 100,
                'is_active' => true
            ],
            [
                'year' => 2025,
                'total_quota' => 120,
                'remaining_quota' => 120,
                'is_active' => true
            ],
            [
                'year' => 2026,
                'total_quota' => 80,
                'remaining_quota' => 80,
                'is_active' => false
            ]
        ];

        foreach ($kipQuotas as $quota) {
            KipQuota::create($quota);
        }
    }
}
