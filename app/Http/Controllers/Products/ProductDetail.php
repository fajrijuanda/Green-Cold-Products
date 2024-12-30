<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetail extends Controller
{
    public function show($slug)
    {
        // Ambil data produk berdasarkan slug
        $product = Product::with(['category'])->where('slug', $slug)->firstOrFail();

        // Cek apakah user sudah login
        if (Auth::check()) {
            // Jika user sudah login, tidak menggunakan layout front
            return view('content.products.product-detail', compact('product'));
        }

        // Jika user belum login, gunakan layout front
        $pageConfigs = ['myLayout' => 'front'];
        return view('content.front-pages.landing-page', ['pageConfigs' => $pageConfigs], compact('product'));
    }
}
