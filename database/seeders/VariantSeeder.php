<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variant;

class VariantSeeder extends Seeder
{
    public function run()
    {
        $variants = [
            ['product_id' => 1, 'type' => 'size', 'value' => '300 x 300 mm'],
            ['product_id' => 1, 'type' => 'length', 'value' => '1130 mm'],
            ['product_id' => 1, 'type' => 'thickness', 'value' => '0.5 mm'],


            ['product_id' => 2, 'type' => 'size', 'value' => '300 x 300 mm'],
            ['product_id' => 2, 'type' => 'length', 'value' => '500 mm'],
            ['product_id' => 2, 'type' => 'thickness', 'value' => '0.5 mm'],

            ['product_id' => 3, 'type' => 'size', 'value' => '305 x 305 mm'],
            ['product_id' => 3, 'type' => 'length', 'value' => '200 mm'],
            ['product_id' => 3, 'type' => 'thickness', 'value' => '0.5 mm'],

            ['product_id' => 4, 'type' => 'size', 'value' => '300 x 350 mm'],
            ['product_id' => 4, 'type' => 'length', 'value' => '100 mm'],
            ['product_id' => 4, 'type' => 'thickness', 'value' => '0.5 mm'],

        ];

        foreach ($variants as $variant) {
            Variant::firstOrCreate(
                [
                    'product_id' => $variant['product_id'],
                    'type' => $variant['type'],
                    'value' => $variant['value']
                ]
            );
        }

        $this->command->info('Variant seeding completed successfully.');
    }
}
