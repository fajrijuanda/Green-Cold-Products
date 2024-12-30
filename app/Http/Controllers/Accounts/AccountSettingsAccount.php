<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AccountSettingsAccount extends Controller
{
    public function index()
    {
        return view('content.pages.pages-account-settings-account');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:255',
            'province' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'zip_code' => 'sometimes|string|max:10',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $changedFields = []; // Untuk mencatat field yang berubah

            // Handle avatar upload jika ada
            if ($request->hasFile('avatar')) {
                $this->handleAvatarUpload($request, $user);
                $changedFields[] = 'Avatar'; // Catat perubahan avatar
            }

            // Update data user dan deteksi perubahan
            $user->fill($request->except(['avatar', '_token']));
            $changes = $user->getDirty(); // Ambil field yang berubah
            unset($changes['updated_at']); // Abaikan updated_at otomatis

            // Proses field yang berubah
            foreach ($changes as $field => $newValue) {
                // Format nama field menjadi ucfirst dan ubah _ menjadi spasi
                $formattedField = ucfirst(str_replace('_', ' ', $field));
                $changedFields[] = $formattedField;
            }

            // Jika ada perubahan, simpan dan log aktivitas
            if (!empty($changedFields)) {
                $user->save();

                // Log aktivitas pengguna
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity' => 'Updated Profile Information',
                    'type' => 'update',
                    'description' => 'Updated profile fields: ' . implode(', ', $changedFields),
                    'activity_date' => now(),
                ]);
            }

            return redirect()->route('account-my-account')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile Update Error: ' . $e->getMessage());
            return redirect()->route('account-settings-account')->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function deactivate(Request $request)
    {
        $user = Auth::user();
        $user->update(['is_active' => false]);

        // Log user activity for account deactivation
        UserActivity::create([
            'user_id' => $user->id,
            'activity' => 'Deactivated their account',
            'type' => 'deactivate',
            'description' => 'User has deactivated their account.',
            'activity_date' => now(),
        ]);

        Auth::logout();
        return redirect()->route('login')->with('success', 'Your account has been deactivated.');
    }
    private function handleAvatarUpload(Request $request, $user)
    {
        $avatarPath = 'assets/img/avatars/';
        $defaultAvatar = $avatarPath . '1.png'; // Gambar default

        // Pastikan direktori avatar ada
        if (!File::exists(base_path($avatarPath))) {
            File::makeDirectory(base_path($avatarPath), 0755, true);
        }

        // Hapus avatar lama jika bukan avatar default
        if ($user->avatar && $user->avatar !== $defaultAvatar && File::exists(base_path($avatarPath . $user->avatar))) {
            File::delete(base_path($avatarPath . $user->avatar));
        }

        // Generate nama unik untuk avatar baru berdasarkan user->name
        $slugName = Str::slug($user->name); // Konversi nama user menjadi slug
        $fileName = $slugName . '.' . $request->file('avatar')->getClientOriginalExtension();

        // Pindahkan file ke direktori tujuan
        $request->file('avatar')->move(base_path($avatarPath), $fileName);

        // Set nilai avatar, tapi jangan langsung simpan di sini
        $user->avatar = $fileName;
    }
}
