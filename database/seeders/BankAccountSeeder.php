<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankAccounts = [
            [
                'bank_name' => 'Bank BRI',
                'account_number' => '0123456789',
                'account_holder' => 'STIK PAR INDONESIA',
                'is_active' => true
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '9876543210',
                'account_holder' => 'STIK PAR INDONESIA',
                'is_active' => true
            ],
            [
                'bank_name' => 'Bank BCA',
                'account_number' => '5555666677',
                'account_holder' => 'STIK PAR INDONESIA',
                'is_active' => false
            ]
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }
    }
}
