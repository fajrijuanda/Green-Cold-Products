<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Detection\MobileDetect; // Pastikan namespace benar
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\UserDeviceLog;

class AccountSettingsSecurity extends Controller
{


    public function index()
    {
        $userId = Auth::id(); // Ambil ID user yang sedang login

        // Ambil data perangkat dari tabel user_device_logs
        $recentDevices = UserDeviceLog::where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->take(5) // Batasi jumlah perangkat yang ditampilkan
            ->get();

        return view('content.pages.pages-account-settings-security', compact('recentDevices'));
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

    private function getPlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Macintosh') !== false) return 'MacOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) return 'iOS';
        if (strpos($userAgent, 'Android') !== false) return 'Android';

        return 'Unknown Platform';
    }

    private function getLocationFromIp($ip)
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Localhost';
        }

        try {
            $locationData = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=city,country,status"));

            if ($locationData && $locationData->status === 'success') {
                return "{$locationData->city}, {$locationData->country}";
            }
        } catch (\Exception $e) {
            Log::error('Failed to get location from IP: ' . $e->getMessage());
        }

        return 'Unknown';
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        $deviceInfo = $this->getDeviceInfo($request);

        Log::info('Password updated on device:', $deviceInfo);

        return back()->with('success', 'Password updated successfully!');
    }

    private function getDeviceInfo(Request $request)
    {
        $detect = new MobileDetect();
        $detect->setUserAgent($request->header('User-Agent'));

        return [
            'browser' => $this->getBrowser($request->header('User-Agent')),
            'platform' => $this->getPlatform($request->header('User-Agent')),
            'device' => $detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Mobile') : 'Desktop',
            'ip_address' => $request->ip(),
            'time' => now(),
        ];
    }
}
