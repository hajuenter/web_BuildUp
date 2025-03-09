<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapanCPBController extends Controller
{
    public function showRekapCPB() {
        return view('screen_admin.rekapan.rekapan_cpb');
    }
}
