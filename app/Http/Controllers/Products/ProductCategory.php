<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\UserActivityLogged;

class ProductCategory extends Controller
{
    public function ProductCategory()
    {

        return view('content.apps.app-ecommerce-category-list');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'name', // Kolom untuk nama kategori
            3 => 'status', // Kolom untuk status
        ];

        $totalData = Category::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length') ?: 10;
        $start = $request->input('start') ?: 0;
        $order = $columns[$request->input('order.0.column')] ?? 'id';
        $dir = $request->input('order.0.dir') ?: 'asc';

        $query = Category::query();
        if ($request->input('search.value')) {
            $search = $request->input('search.value');
            $query->where('name', 'LIKE', "%{$search}%");
            $totalFiltered = $query->count();
        }

        $categories = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];

        foreach ($categories as $category) {
            $nestedData['id'] = $category->id;
            $nestedData['name'] = $category->name;
            $nestedData['category_detail'] = $category->category_detail;
            $nestedData['cat_image'] = $category->cat_image;
            $nestedData['status'] = $category->status;
            $nestedData['action'] = $category->id;
            $data[] = $nestedData;
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'categoryTitle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Published,Scheduled,Inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->categoryTitle); // Generate slug dari categoryTitle

        // Pastikan slug unik
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $imageName = null;

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Generate nama file berdasarkan slug dan ekstensi asli file
            $extension = $image->getClientOriginalExtension(); // Dapatkan ekstensi file
            $imageName = $slug . '.' . $extension; // Contoh: nama-slug.jpg

            // Path untuk menyimpan gambar
            $assetsPath = base_path('assets/img/categories'); // Sesuaikan dengan path ke folder assets Anda

            // Buat folder jika belum ada
            if (!file_exists($assetsPath)) {
                mkdir($assetsPath, 0777, true);
            }

            // Pindahkan file ke folder assets
            $image->move($assetsPath, $imageName);
        }

        // Buat kategori baru
        try {
            $category = Category::create([
                'name' => $request->categoryTitle,
                'slug' => $slug,
                'category_detail' => $request->description,
                'status' => $request->status,
                'cat_image' => $imageName, // Simpan hanya nama file
            ]);
            event(new UserActivityLogged(
                Auth::user(),
                'Create Category',
                "Created category {$category->name}"
            ));
        } catch (\Exception $e) {
            Log::error('Failed to create category: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create category'], 500);
        }

        // Log untuk debugging
        Log::info('Category created successfully:', [
            'name' => $request->categoryTitle,
            'slug' => $slug,
            'category_detail' => $request->description,
            'status' => $request->status,
            'cat_image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category,
        ], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json([
            'category' => $category,
        ]);
    }

    //Edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->category_detail,
            'image' => $category->cat_image, // Tambahkan jika ada gambar
            'status' => $category->status, // Tambahkan jika ada status
        ]);
    }
    public function update(Request $request, $id)
    {
        Log::info('Update Request Data:', $request->all());
    
        $category = Category::findOrFail($id);
        Log::info('Category Before Update:', $category->toArray());
    
        // Validasi input
        $validatedData = $request->validate([
            'categoryTitle' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'sometimes|in:Published,Scheduled,Inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Persiapkan data untuk pembaruan
        $updateData = [];
    
        // Periksa apakah categoryTitle diubah
        if ($request->filled('categoryTitle') && $request->categoryTitle !== $category->name) {
            $slug = Str::slug($request->categoryTitle);
            $originalSlug = $slug;
            $counter = 1;
    
            // Pastikan slug unik
            while (Category::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }
    
            $updateData['name'] = $request->categoryTitle;
            $updateData['slug'] = $slug;
        } else {
            // Gunakan slug lama jika tidak ada perubahan pada categoryTitle
            $slug = $category->slug;
        }
    
        // Periksa dan tambahkan deskripsi jika ada perubahan
        if ($request->has('description') && $request->description !== $category->category_detail) {
            $updateData['category_detail'] = $request->description;
        }
    
        // Periksa dan tambahkan status jika ada perubahan
        if ($request->has('status') && $request->status !== $category->status) {
            $updateData['status'] = $request->status;
        }
    
        // Handle upload gambar baru
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Gunakan slug sebagai nama file dengan ekstensi asli
            $imageName = "{$slug}_" . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('assets/img/categories'), $imageName);
            $updateData['cat_image'] = $imageName;
        } else {
            // Gunakan gambar yang ada jika tidak ada file baru diunggah
            $updateData['cat_image'] = $category->cat_image;
        }
    
        // Jalankan pembaruan jika ada perubahan
        if (!empty($updateData)) {
            $category->fill($updateData);

            // Ambil perubahan data menggunakan getDirty()
            $changes = $category->getDirty();

            if (!empty($changes)) {
                // Simpan data yang diubah
                $category->save();

                // Format perubahan field dan nilai
                $changedDetails = [];
                foreach ($changes as $field => $newValue) {
                    $originalValue = $category->getOriginal($field);

                    // Format nama field menjadi lebih deskriptif
                    $formattedField = ucwords(str_replace('_', ' ', $field));

                    // Tambahkan detail perubahan ke array
                    $changedDetails[] = "{$formattedField}: '{$originalValue}' → '{$newValue}'";
                }

                // Gabungkan perubahan menjadi string
                $changedDetailsString = implode(', ', $changedDetails);

                // Log aktivitas pengguna
                event(new UserActivityLogged(
                    Auth::user(),
                    'Update Category',
                    "Updated category {$category->name}. Changes: {$changedDetailsString}"
                ));
            }
        }

        // Refresh data kategori
        $category->refresh();
    
        Log::info('Category After Update:', $category->toArray());
    
        // Kembalikan respons
        return response()->json([
            'message' => 'Category updated successfully!',
            'category' => $category,
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $categoryName = $category->name; // Simpan nama sebelum dihapus
            $category->delete(); // Soft delete
            // Panggil Event
            event(new UserActivityLogged(
                Auth::user(),
                'Delete Category',
                "Deleted category {$categoryName}"
            ));
            return response()->json(['message' => 'Category soft-deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category: ' . $e->getMessage()], 500);
        }
    }
}
