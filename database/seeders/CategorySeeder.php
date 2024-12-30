<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Data kategori contoh
        $categories = [
            [
                'name' => 'Ducting Exhaust',
                'slug' => 'ducting-exhaust',
                'status' => 'Publish',
                'cat_image' => 'ducting-exhaust.png',
                'category_detail' => 'High-quality ducting systems for efficient exhaust solutions',
            ],
            [
                'name' => 'Exhaust Duct',
                'slug' => 'exhaust-duct',
                'status' => 'Publish',
                'cat_image' => 'exhaust-duct.png',
                'category_detail' => 'Durable exhaust ducts suitable for various applications',
            ],
            [
                'name' => 'Outside Air Duct',
                'slug' => 'outside-air-duct',
                'status' => 'Scheduled',
                'cat_image' => 'outside-air-duct.png',
                'category_detail' => 'Optimized air duct solutions for outside air flow systems',
            ],
            [
                'name' => 'Ducting Smoke Exhaust',
                'slug' => 'ducting-smoke-exhaust',
                'status' => 'Inactive',
                'cat_image' => 'ducting-smoke-exhaust.png',
                'category_detail' => 'Reliable ducting systems for smoke exhaust applications',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
