<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Cari produk berdasarkan ID
        $product = Product::find(1); // Ganti dengan ID yang relevan atau gunakan logika dinamis

        if (!$product) {
            $this->command->error('Product not found. Seeder aborted.');
            return;
        }

        // Data project yang akan di-seed
        $project = [
            'product_id' => $product->id,
            'project_id' => 'LKI-' . $product->company. '01', // Perbaiki akses data 'company'
            'project_name' => $product->company. ' CP203', // Perbaiki akses data 'company'
            'location' => 'ECS-DUCT SF01 & TS01',
            'customer' => 'SHINRYO',
            'date_delivery' => now(),
        ];

        // Seed data ke tabel projects
        Project::firstOrCreate(
            ['product_id' => $project['product_id'], 'project_id' => $project['project_id']],
            $project
        );

        $this->command->info('Project seeding completed successfully.');
    }
}
