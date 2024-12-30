<?php

namespace App\Http\Controllers\Products;

use App\Events\UserActivityLogged;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductList extends Controller
{
  public function ProductList()
  {
    $user = Auth::user();

    // Filter produk berdasarkan user atau project yang sama
    if ($user->role !== 'admin') {
      $products = Product::where('project', $user->project)
        ->withoutTrashed()
        ->get();
      $activeProducts = Product::where('project', $user->project)
        ->where('status', 'Published')
        ->withoutTrashed()
        ->count();
      $inactiveProducts = Product::where('project', $user->project)
        ->where('status', 'Inactive')
        ->withoutTrashed()
        ->count();
    } else {
      $products = Product::withoutTrashed()->get();
      $activeProducts = Product::where('status', 'Published')
        ->withoutTrashed()
        ->count();
      $inactiveProducts = Product::where('status', 'Inactive')
        ->withoutTrashed()
        ->count();
    }

    // Data minggu lalu untuk total produk
    $lastWeekTotalProducts = Product::withoutTrashed()
      ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
      ->count();

    // Hitung persentase perubahan total produk
    $totalProductsChange = $lastWeekTotalProducts > 0
      ? round((($products->count() - $lastWeekTotalProducts) / $lastWeekTotalProducts) * 100, 2)
      : 100; // Jika minggu lalu tidak ada data, anggap pertumbuhan sebagai 100%

    // Hitung jumlah kategori
    $categoriesCount = Category::count(); // Asumsi Anda memiliki model Category

    // Data minggu lalu
    $lastWeekActiveProducts = Product::where('status', 'Published')
      ->withoutTrashed()
      ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
      ->count();

    $lastWeekInactiveProducts = Product::where('status', 'Inactive')
      ->withoutTrashed()
      ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
      ->count();

    // Hitung persentase perubahan
    $activeProductsChange = $lastWeekActiveProducts > 0
      ? round((($activeProducts - $lastWeekActiveProducts) / $lastWeekActiveProducts) * 100, 2)
      : ($activeProducts > 0 ? 100 : 0);

    $inactiveProductsChange = $lastWeekInactiveProducts > 0
      ? round((($inactiveProducts - $lastWeekInactiveProducts) / $lastWeekInactiveProducts) * 100, 2)
      : ($inactiveProducts > 0 ? 100 : 0);

    return view('content.products.product-list', compact(
      'products',
      'activeProducts',
      'inactiveProducts',
      'categoriesCount',
      'activeProductsChange',
      'inactiveProductsChange',
      'totalProductsChange'
    ));
  }



  public function index(Request $request)
  {
    $user = Auth::user();
    $columns = [
      1 => 'id',
      2 => 'product_name',
      3 => 'category_id',
      4 => 'project',
      5 => 'created_by',
      6 => 'status',
    ];

    $totalData = Product::count();
    $totalFiltered = $totalData;

    $limit = $request->input('length') ?: 10;
    $start = $request->input('start') ?: 0;
    $order = $columns[$request->input('order.0.column')] ?? 'id';
    $dir = $request->input('order.0.dir') ?: 'asc';

    $query = Product::query()->with('category');
     // Filter berdasarkan role
    if ($user->role !== 'admin') {
      $query->where('project', $user->project);
    }

    // Filter Search
    if ($request->input('search.value')) {
      $search = $request->input('search.value');
      $query->where('product_name', 'like', "%{$search}%")
        ->orWhereHas('category', function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%");
        })
        ->orWhere('project', 'like', "%{$search}%")
        ->orWhere('created_by', 'like', "%{$search}%");
    }

    // Filter Status
    if ($request->input('status')) {
      $query->where('status', $request->input('status'));
    }

    // Filter Category
    if ($request->input('category')) {
      $query->whereHas('category', function ($q) use ($request) {
        $q->where('name', $request->input('category'));
      });
    }
    $totalFiltered = $query->count();

    // Get Data
    $products = $query->offset($start)
      ->limit($limit)
      ->orderBy($order, $dir)
      ->get();

    // Format Data
    $data = [];
    foreach ($products as $product) {
      $nestedData['id'] = $product->id;
      $nestedData['product_name'] = $product->product_name;
      $nestedData['description'] = $product->description;
      $nestedData['image'] = $product->image;
      $nestedData['slug'] = $product->slug;
      $nestedData['category'] = $product->category->name;
      $nestedData['created_by'] = $product->creator->name;
      $nestedData['avatar'] = $product->creator->avatar;
      $nestedData['project'] = $product->project;
      $nestedData['status'] = $product->status;

      $data[] = $nestedData;
    }

    return response()->json([
      'draw' => intval($request->input('draw')),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $totalFiltered,
      'data' => $data,
    ]);
  }

  public function destroy($id)
  {
    $product = Product::where('id', $id)->first();

    if (!$product) {
      return response()->json(['success' => false, 'message' => 'Product not found!'], 404);
    }

    $productName = $product->product_name;
    $product->delete(); // Assuming soft delete
    // Log aktivitas menggunakan event
    event(new UserActivityLogged(
      Auth::user(),
      'Delete Product',
      "Deleted product {$productName}"
    ));

    return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
  }
}
