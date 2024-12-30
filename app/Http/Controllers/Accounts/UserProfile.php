<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

class UserProfile extends Controller
{
    public function index()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Ambil aktivitas pengguna dari tabel UserActivity
        $activities = UserActivity::where('user_id', $user->id)
            ->orderBy('activity_date', 'desc')
            ->take(10) // Batas 10 aktivitas terakhir
            ->get();

        return view('content.pages.pages-profile-user', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }
}
