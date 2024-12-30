<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // CompanySeeder::class,
            UserSeeder::class,
            // CategorySeeder::class,
            // ProductSeeder::class,
            // VariantSeeder::class,
            // ProjectSeeder::class,
        ]);
    }
}
