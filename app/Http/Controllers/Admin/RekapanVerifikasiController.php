<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapanVerifikasiController extends Controller
{
    public function showRekapVerif()
    {
        return view('screen_admin.rekapan.rekapan_verif');
    }
}
