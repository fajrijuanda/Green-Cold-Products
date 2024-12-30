<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordCover extends Controller
{
    /**
     * Menampilkan halaman reset password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // Mengambil parameter token dan email dari URL
        $token = $request->query('token');
        $email = $request->query('email');

        // Validasi jika token atau email tidak tersedia
        if (!$token || !$email) {
            return redirect()->route('auth-forgot-password')->withErrors('Invalid or missing reset password link.');
        }

        // Konfigurasi halaman
        $pageConfigs = ['myLayout' => 'blank'];

        // Menampilkan halaman reset password dengan token dan email
        return view('content.authentications.auth-reset-password-cover', [
            'pageConfigs' => $pageConfigs,
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Memproses pembaruan password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', // Password harus sama dengan konfirmasi
        ]);

        // Reset password menggunakan Laravel Password Broker
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Perbarui password user
                $user->forceFill([
                    'password' => Hash::make($password), // Hash password baru
                ])->save();
            }
        );

        // Jika berhasil
        if ($status === Password::PASSWORD_RESET) {
            
            return redirect()->route('auth-login-cover')->with('success', 'Your password has been reset successfully!');
        }

        // Jika gagal
        return back()->withErrors(['email' => __($status)]);
    }
}
