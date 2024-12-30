<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\UserActivityLogged;

class UserManagement extends Controller
{

  /**
   * Periksa role di setiap metode.
   */
  private function checkRole()
  {
    if (Auth::check() && Auth::user()->role === 'user') {
      return redirect()->route('error.not-authorized')->send();
    }
  }

  /**
   * Redirect to user-management view.
   */
  public function UserManagement()
  {
    $this->checkRole(); // Cek role

    $users = User::all();
    $totalUser = $users->count();
    $verified = User::whereNotNull('email_verified_at')->count();
    $notVerified = User::whereNull('email_verified_at')->count();
    $usersUnique = $users->unique('email');
    $userDuplicates = $totalUser - $usersUnique->count();

    // Hitung persentase
    $verifiedPercentage = $totalUser > 0 ? round(($verified / $totalUser) * 100, 2) : 0;
    $notVerifiedPercentage = $totalUser > 0 ? round(($notVerified / $totalUser) * 100, 2) : 0;
    $duplicatePercentage = $totalUser > 0 ? round(($userDuplicates / $totalUser) * 100, 2) : 0;

    return view('content.users.user-management', [
      'totalUser' => $totalUser,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'userDuplicates' => $userDuplicates,
      'verifiedPercentage' => $verifiedPercentage,
      'notVerifiedPercentage' => $notVerifiedPercentage,
      'duplicatePercentage' => $duplicatePercentage,
    ]);
  }


  /**
   * Mengembalikan data statistik user dalam format JSON
   */
  public function statistics()
  {
    $this->checkRole(); // Cek role
    $users = User::all();
    $userCount = $users->count();
    $verified = User::whereNotNull('email_verified_at')->count();
    $notVerified = User::whereNull('email_verified_at')->count();
    $usersUnique = $users->unique('email');
    $userDuplicates = $users->count() - $usersUnique->count();

    return response()->json([
      'totalUser' => $userCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'userDuplicates' => $userDuplicates,
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request)
  {
    $this->checkRole(); // Cek role
    $columns = [
      1 => 'id',
      2 => 'name',
      3 => 'project',
      4 => 'email',
      5 => 'email_verified_at',
    ];

    $totalData = User::count();
    $totalFiltered = $totalData;

    $limit = $request->input('length') ?: 10;
    $start = $request->input('start') ?: 0;
    $order = $columns[$request->input('order.0.column')] ?? 'id';
    $dir = $request->input('order.0.dir') ?: 'asc';

    if (empty($request->input('search.value'))) {
      $users = User::select('id', 'first_name', 'last_name', 'project', 'email', 'email_verified_at', 'avatar')
        ->offset($start)
        ->limit($limit)
        ->orderBy($order === 'name' ? 'first_name' : $order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');
      $users = User::select('id', 'first_name', 'last_name', 'email', 'email_verified_at', 'avatar')
        ->where('id', 'LIKE', "%{$search}%")
        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order === 'name' ? 'first_name' : $order, $dir)
        ->get();

      $totalFiltered = User::where('id', 'LIKE', "%{$search}%")
        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];
    $fakeId = $start + 1;

    foreach ($users as $user) {
      $nestedData['id'] = $user->id;
      $nestedData['fake_id'] = $fakeId++;
      $nestedData['name'] = $user->name;
      $nestedData['project'] = $user->project;
      $nestedData['email'] = $user->email;
      $nestedData['email_verified_at'] = $user->email_verified_at;
      $nestedData['avatar'] = $user->avatar;
      $nestedData['action'] = '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $user->id . '">Edit</button>'
        . '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $user->id . '">Delete</button>';

      $data[] = $nestedData;
    }

    return response()->json([
      'draw' => intval($request->input('draw')),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $totalFiltered,
      'data' => $data,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $this->checkRole(); // Cek role
    return view('content.users.create-user');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $this->checkRole(); // Cek role
    $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:8',
      'project' => 'required|string',
    ]);

    $user = User::create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'email_verified_at' => now(),
      'password' => Hash::make($request->password),
      'project' => $request->project,
      'role' => 'user',
      'avatar' => '1.png'
    ]);

    // Panggil event untuk mencatat aktivitas

    event(new UserActivityLogged(
      Auth::user(), // User yang melakukan aksi
      'Create New User',
      "Created user {$user->first_name} {$user->last_name} with email {$user->email}"
    ));

    return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $this->checkRole(); // Cek role
    $user = User::findOrFail($id);
    return response()->json($user);
  }

  /**
   * Show the form for editing the specified resource.
   */
  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->checkRole(); // Cek role
    $user = User::findOrFail($id);

    return response()->json([
      'id' => $user->id,
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
      'email' => $user->email,
      'project' => $user->project,
      'role' => $user->role, // Tambahkan jika ada role
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $this->checkRole(); // Cek role
    $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'project' => 'required|string',
    ]);

    $user = User::findOrFail($id);

    $user->update([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'project' => $request->project,
    ]);

    // Panggil event untuk mencatat aktivitas
    event(new UserActivityLogged(
      Auth::user(),
      'Update User',
      "Updated user {$user->first_name} {$user->last_name} with email {$user->email}"
    ));

    return response()->json([
      'message' => 'User updated successfully',
      'user' => $user
    ]);
  }


  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $this->checkRole(); // Cek role
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
  }
}
