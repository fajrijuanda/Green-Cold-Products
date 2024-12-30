<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $user = Auth::user();

        // Query produk dengan relasi category
        $query = $user->role === 'admin'
            ? Product::with(['category'])
            : Product::with(['category'])->where('created_by', $user->id);

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('project', 'like', "%{$search}%");
            });
        
            // Prioritaskan hasil yang memiliki kecocokan langsung
            $query->orderByRaw("
                CASE
                    WHEN product_name LIKE '{$search}' THEN 1
                    WHEN product_name LIKE '{$search}%' THEN 2
                    WHEN product_name LIKE '%{$search}%' THEN 3
                    WHEN project LIKE '{$search}' THEN 4
                    WHEN project LIKE '{$search}%' THEN 5
                    WHEN project LIKE '%{$search}%' THEN 6
                    ELSE 7
                END
            ");
        }
        
        
        $products = $query->paginate(6); // Paginasi 6 produk per halaman

        // Tentukan warna badge untuk setiap kategori
        $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary', 'dark'];
        $categoryColors = $categories->mapWithKeys(function ($category) use ($colors) {
            $colorIndex = crc32($category->slug) % count($colors);
            return [$category->slug => $colors[$colorIndex]];
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('content.dashboard._product-list', compact('products', 'categoryColors'))->render(),
                'pagination' => view('content.dashboard._pagination', compact('products'))->render(),
                'current_count' => $products->count(),
            ]);
        }

        return view('content.dashboard.dashboard', compact('products', 'categories', 'categoryColors'));
    }
}
