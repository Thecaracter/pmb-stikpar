<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configurations = [
            [
                'key' => 'max_upload_size',
                'value' => '2048',
                'description' => 'Maksimal ukuran file upload dalam KB'
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,jpg,jpeg,png',
                'description' => 'Tipe file yang diizinkan untuk upload'
            ],
            [
                'key' => 'contact_email',
                'value' => 'pmb@stikpar.ac.id',
                'description' => 'Email kontak untuk pendaftaran'
            ],
            [
                'key' => 'contact_phone',
                'value' => '021-12345678',
                'description' => 'Telepon kontak untuk pendaftaran'
            ]
        ];

        foreach ($configurations as $config) {
            Configuration::create($config);
        }
    }
}