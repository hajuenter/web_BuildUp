<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetugasProfileController extends Controller
{
    public function showPetugasProfile()
    {
        return view('screen_petugas.profile.profile');
    }
}
