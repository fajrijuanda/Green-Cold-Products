<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Detection\MobileDetect;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivity; // Pastikan model ini ada
use Carbon\Carbon;
use App\Models\UserDeviceLog;
use Illuminate\Support\Str;

class Authentication extends Controller
{
  public function index()
  {
    if (Auth::check()) { //+
      return redirect()->route('dashboard');
    }

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-cover', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember-me');

    if (Auth::attempt($credentials, $remember)) {
      $request->session()->regenerate();

      $user = Auth::user();
      $sessionId = session()->getId();
      $userAgent = $request->header('User-Agent');
      $ipAddress = $request->ip();

       // Perbarui remember_token di tabel users
       if ($remember) {
        $user->update([
            'remember_token' => Str::random(60), // Buat token baru
        ]);
    }
      // Gunakan MobileDetect untuk mendapatkan informasi perangkat
      $detect = new MobileDetect();
      $detect->setUserAgent($userAgent);

      $device = $detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Mobile') : 'Desktop';
      $location = $this->getLocationFromIp($ipAddress); // Dapatkan lokasi dari IP

      // **Update session dengan data tambahan**
      DB::table('sessions')->updateOrInsert(
        ['id' => $sessionId], // Kondisi untuk menemukan sesi berdasarkan ID
        [
          'user_id' => $user->id,
          'ip_address' => $ipAddress,
          'user_agent' => $userAgent,
          'device' => $device,
          'location' => $location,
          'payload' => session()->getHandler()->read($sessionId), // Menyimpan payload sesi
          'last_activity' => Carbon::now()->timestamp, // Simpan sebagai UNIX timestamp
        ]
      );

      // **Sinkronkan ke tabel UserDeviceLogs**
      $sessionData = DB::table('sessions')->where('id', $sessionId)->first();

      if ($sessionData) {
        UserDeviceLog::create([
            'user_id' => $user->id,
            'ip_address' => $sessionData->ip_address,
            'browser' => $this->getBrowser($sessionData->user_agent),
            'platform' => $this->getPlatform($sessionData->user_agent),
            'device' => $sessionData->device,
            'location' => $sessionData->location,
            'last_activity' => now(),
        ]);
    }    

      // Catat aktivitas login
      UserActivity::create([
        'user_id' => $user->id,
        'activity' => 'Logged in',
        'type' => 'login',
        'description' => "Logged in from {$device}, {$location}, using {$this->getBrowser($userAgent)}",
        'activity_date' => now(),
      ]);

      return redirect()->intended('dashboard');
    }

    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
    $user = Auth::user();

    // Catat aktivitas logout sebelum logout
    if ($user) {
      UserActivity::create([
        'user_id' => $user->id,
        'activity' => 'Logged out',
        'type' => 'logout',
        'description' => 'User logged out successfully.',
        'activity_date' => now(),
      ]);
    }

    Auth::logout();

    DB::table('sessions')->where('id', session()->getId())->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }

  private function getLocationFromIp($ip)
  {
    if ($ip === '127.0.0.1' || $ip === '::1') {
      return 'Localhost';
    }
    try {
      $locationData = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));

      if ($locationData && $locationData->status === 'success') {
        return "{$locationData->city}, {$locationData->country}";
      }
    } catch (\Exception $e) {
      // Log atau tangani kesalahan jika diperlukan
    }

    return 'Unknown Location';
  }

  private function getBrowser($userAgent)
  {
    if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
    if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
    if (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) return 'Safari';
    if (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) return 'Opera';
    if (strpos($userAgent, 'Edge') !== false) return 'Edge';
    if (strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';

    return 'Unknown Browser';
  }

  public function logoutOnClose(Request $request)
  {
    if ($request->user_id && Auth::id() == $request->user_id) {
      // Simpan aktivitas logout
      UserActivity::create([
        'user_id' => $request->user_id,
        'activity' => 'Logout',
        'type' => 'Tab Close',
        'description' => 'User logged out by closing the tab or browser window.',
        'activity_date' => now(),
      ]);

      Auth::logout(); // Logout user

      DB::table('sessions')->where('id', session()->getId())->delete();

      $request->session()->invalidate();
      $request->session()->regenerateToken();
    }

    return response()->json(['message' => 'Logged out'], 200);
  }

  private function getPlatform($userAgent) // Tambahkan fungsi ini
  {
    if (strpos($userAgent, 'Windows') !== false) return 'Windows';
    if (strpos($userAgent, 'Macintosh') !== false) return 'MacOS';
    if (strpos($userAgent, 'Linux') !== false) return 'Linux';
    if (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) return 'iOS';
    if (strpos($userAgent, 'Android') !== false) return 'Android';

    return 'Unknown Platform';
  }
}
