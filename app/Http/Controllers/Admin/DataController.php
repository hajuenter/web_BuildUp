<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataCPB;
use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function showDataCPB()
    {
        $dataCPB = DataCPB::all();
        return view('screen_admin.data.data_cpb', compact('dataCPB'));
    }

    public function showDataRole(Request $request)
    {
        $perPage = $request->input('perPage', 5); // Default 5 jika tidak ada input

        // Jika pilih "Semua", ambil semua data tanpa pagination
        if ($perPage == "all") {
            $dataRole = User::whereIn('role', ['petugas', 'user'])->get();
        } else {
            $dataRole = User::whereIn('role', ['petugas', 'user'])->paginate($perPage);
        }

        return view('screen_admin.data.data_role', compact('dataRole', 'perPage'));
    }

    public function verifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        return back()->with('successY', 'User berhasil diaktifkan!');
    }

    public function unverifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = null;
        $user->save();

        return back()->with('successG', 'User berhasil dinonaktifkan!');
    }
}
