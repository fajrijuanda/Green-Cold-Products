<?php

namespace App\Http\Controllers\Projects;

use App\Events\UserActivityLogged;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectList extends Controller
{
  public function ProjectList()
  {
    $user = Auth::user();

    if ($user->role !== 'admin') {
      // Ambil daftar proyek yang terkait dengan pengguna saat ini
      $userProject = $user->project;

      // Filter produk terkait berdasarkan user dan proyek
      $products = Product::whereNull('deleted_at') // Produk belum dihapus
        ->where(function ($query) use ($user, $userProject) {
          $query->where('created_by', $user->id) // Produk dibuat oleh pengguna saat ini
            ->orWhereHas('creator', function ($userQuery) use ($userProject) {
              $userQuery->where('project', $userProject); // Pengguna lain dengan proyek yang sama
            });
        })->get();

      // Filter proyek terkait dengan produk yang difilter di atas
      $projects = Project::whereHas('product', function ($query) use ($products) {
        $query->whereIn('id', $products->pluck('id')); // Produk terkait dengan proyek
      })->get();
    } else {
      // Admin: Lihat semua proyek dan produk yang belum dihapus
      $projects = Project::whereHas('product', function ($query) {
        $query->whereNull('deleted_at'); // Produk belum dihapus
      })->get();

      $products = Product::whereNull('deleted_at')->get(); // Semua produk yang belum dihapus
    }

    // Hitung total proyek
    $totalProjects = $projects->count();

    // Hitung proyek aktif
    $activeProjects = $projects->where('status', 'Active')->count();

    // Hitung proyek tidak aktif (soft delete)
    $inactiveProjects = Project::onlyTrashed()
      ->whereHas('product', function ($query) {
        $query->whereNull('deleted_at'); // Produk terkait belum dihapus
      })->count();

    // Hitung pelanggan unik
    $totalCustomers = $projects->unique('customer')->count();

    // Perubahan data mingguan
    $lastWeekProjects = Project::whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
      ->whereHas('product', function ($query) {
        $query->whereNull('deleted_at'); // Hanya produk aktif
      })->count();

    $totalProjectsChange = $lastWeekProjects > 0
      ? round((($totalProjects - $lastWeekProjects) / $lastWeekProjects) * 100, 2)
      : ($totalProjects > 0 ? 100 : 0);

    $lastWeekActiveProjects = Project::where('status', 'Active')
      ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
      ->whereHas('product', function ($query) {
        $query->whereNull('deleted_at'); // Hanya produk aktif
      })->count();

    $activeProjectsChange = $lastWeekActiveProjects > 0
      ? round((($activeProjects - $lastWeekActiveProjects) / $lastWeekActiveProjects) * 100, 2)
      : ($activeProjects > 0 ? 100 : 0);

    $lastWeekInactiveProjects = Project::onlyTrashed()
      ->whereBetween('deleted_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
      ->whereHas('product', function ($query) {
        $query->whereNull('deleted_at'); // Hanya produk terkait yang belum dihapus
      })->count();

    $inactiveProjectsChange = $lastWeekInactiveProjects > 0
      ? round((($inactiveProjects - $lastWeekInactiveProjects) / $lastWeekInactiveProjects) * 100, 2)
      : ($inactiveProjects > 0 ? 100 : 0);

    return view('content.projects.project-list', compact(
      'projects',
      'totalProjects',
      'activeProjects',
      'inactiveProjects',
      'totalCustomers',
      'totalProjectsChange',
      'activeProjectsChange',
      'inactiveProjectsChange',
      'products'
    ));
  }


  public function index(Request $request)
  {

    $columns = [
      1 => 'id',
      2 => 'project_id',
      3 => 'project_name',
      4 => 'product_name',
      5 => 'customer',
      6 => 'location',
      7 => 'date_delivery',
    ];

    $totalData = Project::whereHas('product', function ($query) {
      $query->whereNull('deleted_at'); // Filter hanya produk yang belum dihapus
    })->count();
    $totalFiltered = $totalData;

    $limit = $request->input('length') ?: 10;
    $start = $request->input('start') ?: 0;
    $order = $columns[$request->input('order.0.column')] ?? 'id';
    $dir = $request->input('order.0.dir') ?: 'asc';

    $user = Auth::user();

    // Jika user bukan admin, ambil proyek terkait berdasarkan user ID dan proyek yang sama
    if ($user->role !== 'admin') {
      // Ambil daftar proyek yang terkait dengan pengguna saat ini
      $userProject = $user->project;

      // Query untuk proyek
      $query = Project::whereHas('product', function ($productQuery) use ($user, $userProject) {
        $productQuery->whereNull('deleted_at') // Hanya produk yang belum dihapus
          ->where(function ($q) use ($user, $userProject) {
            $q->where('created_by', $user->id) // Produk dibuat oleh pengguna saat ini
              ->orWhereHas('creator', function ($creatorQuery) use ($userProject) {
                $creatorQuery->where('project', $userProject); // Produk pengguna lain dengan proyek yang sama
              });
          });
      });
    } else {
      // Admin: Query untuk semua proyek dengan produk yang belum dihapus
      $query = Project::whereHas('product', function ($query) {
        $query->whereNull('deleted_at'); // Hanya produk aktif
      });
    }

    // Pencarian (search)
    if ($request->input('search.value')) {
      $search = $request->input('search.value');
      $query->where(function ($q) use ($search) {
        $q->where('project_id', 'like', "%{$search}%")
          ->orWhere('project_name', 'like', "%{$search}%")
          ->orWhereHas('product', function ($productQuery) use ($search) {
            $productQuery->where('product_name', 'like', "%{$search}%")
              ->whereNull('deleted_at'); // Filter hanya produk aktif
          })
          ->orWhere('customer', 'like', "%{$search}%")
          ->orWhere('location', 'like', "%{$search}%")
          ->orWhere('date_delivery', 'like', "%{$search}%");
      });

      $totalFiltered = $query->count(); // Hitung ulang total data yang difilter
    }

    $projects = $query->offset($start)
      ->limit($limit)
      ->orderBy($order, $dir)
      ->get();

    $data = [];

    foreach ($projects as $project) {
      $nestedData['id'] = $project->id;
      $nestedData['project_id'] = $project->project_id;
      $nestedData['project_name'] = $project->project_name;
      $nestedData['product_name'] = $project->product->product_name;
      $nestedData['category_name'] = $project->product->category->abbreviation;
      $nestedData['image'] = $project->product->image;
      $nestedData['size'] = $project->product->size;
      $nestedData['length'] = $project->product->length;
      $nestedData['thickness'] = $project->product->thickness;
      $nestedData['customer'] = $project->customer;
      $nestedData['location'] = $project->location;
      $nestedData['date_delivery'] = $project->date_delivery;
      // Tambahkan properti qr_code_path
      $nestedData['qr_code_path'] = asset('assets/img/qr-codes/' . $project->product->qr_code_path);

      $data[] = $nestedData;
    }

    return response()->json([
      'draw' => $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $totalFiltered,
      'data' => $data,
    ]);
  }

  public function store(Request $request)
  {
    try {
      // Validasi input
      $validatedData = $request->validate([
        'project_id' => 'required|string|max:255',
        'project_name' => 'required|string|max:255',
        'product_id' => 'required|integer|exists:products,id',
        'customer' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date_delivery' => 'required|date',
      ]);

      // Tambahkan status default
      $validatedData['status'] = 'Active';
      // Simpan data ke database
      $project = Project::create($validatedData);

      // Log aktivitas
      event(new UserActivityLogged(
        Auth::user(),
        'Create Project',
        "Created project {$project->project_name} for customer {$project->customer}"
      ));
      return response()->json([
        'success' => true,
        'message' => 'Project berhasil disimpan!',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function getProjectById($id)
  {
    try {
      $project = Project::with('product.category')->findOrFail($id);

      return response()->json([
        'success' => true,
        'data' => $project,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Project not found',
      ], 404);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $validatedData = $request->validate([
        'project_id' => 'required|string|max:255',
        'project_name' => 'required|string|max:255',
        'product_id' => 'required|integer|exists:products,id',
        'customer' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date_delivery' => 'required|date',
      ]);

      $project = Project::findOrFail($id);
      // Simpan perubahan ke model
      $project->fill($validatedData);

      // Ambil perubahan data menggunakan getDirty()
      $changes = $project->getDirty();

      if (!empty($changes)) {
        $changedDetails = [];
        foreach ($changes as $field => $newValue) {
          $originalValue = $project->getOriginal($field); // Ambil nilai lama

          // Format nilai jika field adalah tanggal
          if ($field === 'date_delivery') {
            $originalValue = \Carbon\Carbon::parse($originalValue)->format('d-m-Y');
            $newValue = \Carbon\Carbon::parse($newValue)->format('d-m-Y');
          }

          // Tambahkan perubahan hanya jika nilai lama dan baru berbeda
          if ($originalValue != $newValue) {
            $formattedField = ucwords(str_replace('_', ' ', $field)); // Format nama field
            $changedDetails[] = "{$formattedField}: '{$originalValue}' → '{$newValue}'";
          }
        }

        if (!empty($changedDetails)) {
          // Simpan perubahan data ke database
          $project->save();

          // Gabungkan perubahan menjadi string
          $changedDetailsString = implode(', ', $changedDetails);

          // Log aktivitas pengguna
          event(new UserActivityLogged(
            Auth::user(),
            'Update Project',
            "Updated project {$project->project_id}. Changes: {$changedDetailsString}"
          ));
        }
      }

      return response()->json([
        'success' => true,
        'message' => 'Project updated successfully!',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update project.',
      ], 500);
    }
  }

  public function destroy($id)
  {
    try {
      $project = Project::findOrFail($id);
      $projectName = $project->project_name; // Simpan nama proyek sebelum dihapus
      $customer = $project->customer;
      $project->delete();

      // Log aktivitas
      event(new UserActivityLogged(
        Auth::user(),
        'Delete Project',
        "Deleted project {$projectName} for customer {$customer}"
      ));

      return response()->json([
        'success' => true,
        'message' => 'Project deleted successfully!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete project. Please try again.'
      ], 500);
    }
  }
}
