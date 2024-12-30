<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'name' => 'LKI',
                'address' => '123 Street, City',
                'phone_number' => '021-12345678',
                'email' => 'lki@example.com',
            ],
            [
                'name' => 'MRT',
                'address' => '456 Avenue, City',
                'phone_number' => '021-87654321',
                'email' => 'mrt@example.com',
            ],
            [
                'name' => 'ABC',
                'address' => '789 Boulevard, City',
                'phone_number' => '021-98765432',
                'email' => 'abc@example.com',
            ]
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate(
                ['name' => $companyData['name']], // Kondisi unik
                [
                    'address' => $companyData['address'],
                    'phone_number' => $companyData['phone_number'], // Tambahkan phone_number
                    'email' => $companyData['email'], // Tambahkan email
                ]
            );
        }

        $this->command->info('Company seeding completed successfully.');
    }
}
