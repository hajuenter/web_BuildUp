<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function showJadwal()
    {
        return view('screen_admin.jadwal.jadwal');
    }
}
