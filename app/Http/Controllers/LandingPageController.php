<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        $showBerita = Berita::latest()->paginate(3);

        if (request()->ajax()) {
            $html = '';

            foreach ($showBerita as $berita) {
                $html .= '
                <div class="row gy-3 mb-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <img src="' . asset('up/berita/' . $berita->photo) . '" alt="' . $berita->judul . '" class="img-fluid rounded">
                    </div>
                    <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="about-content ps-0 ps-lg-3">
                            <h3>' . $berita->judul . '</h3>
                            <p class="fst-italic">' . $berita->isi . '</p>
                            <p>Penulis: <strong>' . $berita->penulis . '</strong></p>
                            <p>Tanggal: ' . Carbon::parse($berita->tanggal)->translatedFormat('d F Y') . '</p>
                        </div>
                    </div>
                </div>';
            }

            if ($showBerita->isEmpty()) {
                $html .= '<div class="alert alert-info text-center">Belum ada berita yang tersedia.</div>';
            }

            $html .= '<div class="d-flex justify-content-center mt-4">'
                . $showBerita->links('pagination::bootstrap-5')
                . '</div>';

            return Response::make($html);
        }

        $showJadwal = Jadwal::all();

        return view('buildup', compact('showBerita', 'showJadwal'));
    }
}
