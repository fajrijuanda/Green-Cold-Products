<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'product_name' => 'Ducting Lurus',
                'category_id' => 1,
                'image' => 'ducting-lurus.png',
                'description' => 'Straight ducting designed for optimal airflow in HVAC systems.',
                'company' => 'MRT',
                'status' => 'Published',
                'created_by' => 'Admin 1 MRT',
            ],
            [
                'product_name' => 'Ducting Reducer',
                'category_id' => 2,
                'image' => 'ducting-reducer.png',
                'description' => 'Reducer ducting for connecting different sizes of duct systems seamlessly.',
                'company' => 'MRT',
                'status' => 'Scheduled',
                'created_by' => 'Admin 2 MRT',
            ],
            [
                'product_name' => 'Duct Box Grille',
                'category_id' => 2,
                'image' => 'duct-box-grille.png',
                'description' => 'Box grille ducting suitable for ventilation and aesthetic designs.',
                'company' => 'MRT',
                'status' => 'Inactive',
                'created_by' => 'Admin 1 MRT',
            ],
            [
                'product_name' => 'Duct Elbow 45°',
                'category_id' => 3,
                'image' => 'duct-elbow-45.png',
                'description' => 'Elbow ducting with a 45° angle for redirecting airflow efficiently.',
                'company' => 'MRT',
                'status' => 'Published',
                'created_by' => 'Admin MRT 3',
            ],
        ];

        foreach ($products as $product) {
            // Generate slug
            $slug = Str::slug($product['product_name']);

            // Buat produk
            $newProduct = Product::create(array_merge($product, [
                'slug' => $slug,
            ]));

            // Generate QR Code
            $qrCode = new QrCode(url('/products/' . $slug));
            $qrCode->getSize(113); // Ukuran QR Code
            $qrCode->getMargin(0); // Hilangkan margin tambahan

            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode);

            // Path untuk menyimpan QR Code
            $qrCodeFileName = $slug . '.png';
            $qrCodePath = public_path('assets/img/qr-codes/' . $qrCodeFileName);

            // Simpan QR Code ke file
            file_put_contents($qrCodePath, $qrCodeImage->getString());

            // Update produk dengan nama file QR Code
            $newProduct->update([
                'qr_code_path' => $qrCodeFileName,
            ]);
        }
    }
}
