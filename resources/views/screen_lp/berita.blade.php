<section id="berita" class="about section light-background">
    <div class="container section-title" data-aos="fade-up">
        <h2>Berita</h2>
        <p><span>Dapatkan Informasi Terbaru Tentang</span> <span class="description-title">Kami</span></p>
    </div>

    <div class="container py-3" id="berita-wrapper">
        @php use Carbon\Carbon; @endphp

        @forelse($showBerita as $berita)
            <div class="row gy-3 mb-4">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('up/berita/' . $berita->photo) }}" alt="{{ $berita->judul }}"
                        class="img-fluid rounded">
                </div>
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="about-content ps-0 ps-lg-3">
                        <h3>{{ $berita->judul }}</h3>
                        <p class="fst-italic">{{ $berita->isi }}</p>
                        <p>Penulis: <strong>{{ $berita->penulis }}</strong></p>
                        <p>Tanggal: {{ Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>
            <hr>
        @empty
            <div class="alert alert-info text-center">Belum ada berita yang tersedia.</div>
        @endforelse

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $showBerita->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchBerita(page);
    });

    function fetchBerita(page) {
        $.ajax({
            url: "?page=" + page,
            success: function(data) {
                $('#berita-wrapper').html(data);
                window.scrollTo({
                    top: document.getElementById("berita").offsetTop - 60,
                    behavior: 'smooth'
                });
            }
        });
    }
</script>
