<?php

namespace App\Http\Controllers\Products;

use App\Events\UserActivityLogged;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant; // Model untuk Variants
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\PngResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductAdd extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();
        $title = 'Add Product';
        return view('content.products.product-add', compact('user', 'categories', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255', // Validasi uniqueness
            'category_id' => 'required|exists:categories,id', // Validasi kategori yang ada
            'description' => 'nullable|string|max:80', // Deskripsi opsional
            'project' => 'required|string', // Validasi untuk project
            'status' => 'required|in:Scheduled,Published,Inactive', // Hanya nilai tertentu
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120', // Validasi gambar
            'size' => 'required|string|max:50', // Menambahkan max jika perlu
            'length' => 'required|string|max:50', // Menambahkan max jika perlu
            'thickness' => 'required|string|max:50', // Menambahkan max jika perlu
        ]);


        // Generate slug untuk produk
        // Generate slug untuk produk
        $slug = Str::slug($request->product_name);

        // Cek apakah slug sudah ada di database
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }


        // Upload gambar produk
        $image = $request->file('image');
        $imageExtension = $image->getClientOriginalExtension();
        $imageFileName = $slug . '.' . $imageExtension;
        $imagePath = base_path('assets/img/products/');

        // Buat folder jika belum ada
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0755, true);
        }

        // Simpan gambar di folder
        $image->move($imagePath, $imageFileName);

        // Simpan produk ke tabel `products`
        $product = Product::create([
            'product_name' => $request->product_name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'project' => $request->project,
            'size' => $request->size,
            'length' => $request->length,
            'thickness' => $request->thickness,
            'image' => $imageFileName, // Simpan path gambar
            'status' => $request->status,
        ]);

        // Simpan dengan relasi creator
        $product->creator()->associate(Auth::user());
        $product->save();

        // Generate QR Code
        $qrCode = new QrCode(url('/products/' . $slug));
        $qrCode->getSize(113); // Ukuran QR Code
        $qrCode->getMargin(0); // Hilangkan margin tambahan

        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);

        // Path untuk menyimpan QR Code
        $qrCodeFileName = $slug . '.png';
        $qrCodePath = base_path('assets/img/qr-codes/' . $qrCodeFileName);

        // Buat folder jika belum ada
        if (!file_exists(base_path('assets/img/qr-codes/'))) {
            mkdir(base_path('assets/img/qr-codes/'), 0755, true);
        }

        // Simpan QR Code ke file
        file_put_contents($qrCodePath, $qrCodeImage->getString());

        // Update produk dengan nama file QR Code
        $product->update([
            'qr_code_path' => $qrCodeFileName,
        ]);
        event(new UserActivityLogged(
            Auth::user(),
            'Create Product',
            "Created product {$product->product_name} in project {$product->project}"
        ));
        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'redirect_url' => route('product-list'),
        ]);
    }

    //Edit by slug
    public function edit($slug)
    {
        $user = Auth::user();
        $categories = Category::all();
        $product = Product::where('slug', $slug)->first();

        // Cek apakah produk ada
        if (!$product) {
            abort(404, 'Product not found');
        }

        // Cek apakah pengguna memiliki akses ke proyek yang sama
        if ($user->project !== $product->project) {
            abort(403, 'You do not have permission to edit this product.');
        }

        $title = 'Edit Product'; // Ubah judul halaman
        return view('content.products.product-add', compact('user', 'categories', 'product', 'title'));
    }


    public function update(Request $request, $slug)
    {
        // Log data request untuk debugging
        Log::info('Request Data:', $request->all());

        // Validasi input
        $validatedData = $request->validate([
            'product_name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|nullable|string:max:80',
            'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:5120', // Validasi file gambar
            'project' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Published,Scheduled,Inactive',
            'size' => 'sometimes|nullable|string',
            'length' => 'sometimes|nullable|string',
            'thickness' => 'sometimes|nullable|string',
        ]);

        try {
            // Cari produk berdasarkan slug
            $product = Product::where('slug', $slug)->firstOrFail();

            // Jika nama produk diubah, buat slug baru yang unik
            if (!empty($validatedData['product_name']) && $validatedData['product_name'] !== $product->product_name) {
                $newSlug = Str::slug($validatedData['product_name']);
                $existingSlugs = Product::where('slug', 'like', "{$newSlug}%")
                    ->where('id', '!=', $product->id)
                    ->pluck('slug')
                    ->toArray();

                // Tambahkan angka jika slug duplikat
                if (in_array($newSlug, $existingSlugs)) {
                    $newSlug .= '-' . (count($existingSlugs) + 1);
                }

                $validatedData['slug'] = $newSlug;
            } else {
                $validatedData['slug'] = $product->slug; // Gunakan slug lama jika tidak diubah
            }

            // Cek apakah ada file gambar baru yang diupload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageExtension = $image->getClientOriginalExtension(); // Dapatkan ekstensi file
                $imageName = $validatedData['slug'] . '.' . $imageExtension; // Nama file berdasarkan slug

                // Hapus gambar lama jika ada
                if ($product->image && file_exists(base_path('assets/img/products/' . $product->image))) {
                    unlink(base_path('assets/img/products/' . $product->image));
                }

                // Pindahkan file gambar baru ke folder yang benar
                $image->move(base_path('assets/img/products'), $imageName);
                $validatedData['image'] = $imageName; // Simpan nama file gambar baru
            } else {
                // Gunakan gambar lama jika tidak ada gambar baru
                $validatedData['image'] = $product->image;
            }

            // Update data produk
            $product->update(array_merge($validatedData, [
                'updated_by' => Auth::id(), // Rekam siapa yang melakukan update
            ]));
            $product->fill($validatedData);

            // Ambil perubahan data menggunakan getDirty(), filter hanya field yang ada di $validatedData
            $changes = array_intersect_key($product->getDirty(), $validatedData);

            if (!empty($changes)) {
                // Simpan perubahan ke database
                $product->save();

                // Format perubahan field dan nilai
                $changedDetails = [];
                foreach ($changes as $field => $newValue) {
                    $originalValue = $product->getOriginal($field); // Nilai sebelum diubah

                    // Format nama field menjadi lebih deskriptif
                    $formattedField = ucwords(str_replace('_', ' ', $field));

                    // Tambahkan ke detail perubahan
                    $changedDetails[] = "{$formattedField}: '{$originalValue}' → '{$newValue}'";
                }

                // Gabungkan perubahan menjadi string
                $changedDetailsString = implode(', ', $changedDetails);

                // Log aktivitas pengguna
                event(new UserActivityLogged(
                    Auth::user(),
                    'Update Product',
                    "Updated product {$product->product_name}. Changes: {$changedDetailsString}"
                ));
            }
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'redirect_url' => route('product-list'),
            ]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah saat update
            Log::error('Error updating product:', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update product.',
            ], 500);
        }
    }
}
