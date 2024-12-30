<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MiscError extends Controller
{
    public function index(Request $request)
    {
        $pageConfigs = ['myLayout' => 'blank'];
        $statusCode = $request->input('status', 404); // Default ke 404
        
        // Optional: Log error
        Log::error('Error Page Accessed', [
            'status_code' => $statusCode,
            'requested_url' => $request->fullUrl(),
            'referer' => $request->header('referer')
        ]);

        return view('content.pages.pages-misc-error', [
            'pageConfigs' => $pageConfigs,
            'statusCode' => $statusCode,
            'message' => $request->input('message', 'Halaman tidak ditemukan')
        ]);
    }
}