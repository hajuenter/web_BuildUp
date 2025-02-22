<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush(); // Hapus semua session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('ggg', 'Anda telah logout.');
    }
}
