<!-- Contact Section -->
<section id="contact" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Kontak Kami</h2>
        <p><span>Butuh Bantuan Hubungi</span> <span class="description-title">Kami</span></p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

            <div class="col-lg-5">

                <div class="info-wrap">
                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-geo-alt flex-shrink-0"></i>
                        <div>
                            <h3>Alamat</h3>
                            <p>Jl. Mastrip No.7 Kel, Nganjuk, Ganung Kidul, Kec. Nganjuk, Kabupaten Nganjuk, Jawa
                                Timur 64419</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-telephone flex-shrink-0"></i>
                        <div>
                            <h3>Hubungi Kami</h3>
                            <p>0358330055</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                        <i class="bi bi-envelope flex-shrink-0"></i>
                        <div>
                            <h3>Email Kami</h3>
                            <p>prkppnganjuk@gmail.com</p>
                        </div>
                    </div><!-- End Info Item -->

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.7432667192843!2d111.90611727500304!3d-7.602893692412099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784baee3d69fd3%3A0x132635346a25bd7f!2sDinas%20Perumahan%20Rakyat%20Kawasan%20Permukiman%20dan%20Pertanahan%20Kabupaten%20Nganjuk!5e0!3m2!1sid!2sid!4v1740065925010!5m2!1sid!2sid"
                        frameborder="0" style="border:0; width: 100%; height: 270px;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="col-lg-7">
                <div id="alert-container"></div>

                <form action="{{ route('kirim.pesan') }}" method="POST" class="php-email-form" data-aos="fade-up"
                    data-aos-delay="200" id="contact-form">
                    @csrf
                    <div class="row gy-4">

                        <div class="col-md-6">
                            <label for="name-field" class="pb-2">Nama</label>
                            <input type="text" name="name" id="name-field" class="form-control" required="">
                        </div>

                        <div class="col-md-6">
                            <label for="email-field" class="pb-2">Email</label>
                            <input type="email" class="form-control" name="email" id="email-field" required="">
                        </div>

                        <div class="col-md-12">
                            <label for="subject-field" class="pb-2">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subject-field" required="">
                        </div>

                        <div class="col-md-12">
                            <label for="message-field" class="pb-2">Pesan</label>
                            <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                        </div>

                        <div class="col-md-12 text-center">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Pesan kamu telah berhasil dikirim. Terima kasih!</div>

                            <button type="submit" id="submit-btn">Kirim Pesan</button>

                        </div>

                    </div>
                </form>
            </div><!-- End Contact Form -->

        </div>

    </div>

</section><!-- /Contact Section -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const alertContainer = document.getElementById('alert-container');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah form reload

            submitBtn.disabled = true;
            submitBtn.innerText = 'Mengirim...';

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    let alertHTML = '';

                    if (data.success) {
                        alertHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${data.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
                        form.reset();
                    } else {
                        alertHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${data.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
                    }

                    alertContainer.innerHTML = alertHTML;
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Kirim Pesan';
                })
                .catch(error => {
                    alertContainer.innerHTML =
                        `<div class="alert alert-danger">Terjadi kesalahan, coba lagi nanti.</div>`;
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Kirim Pesan';
                    console.error(error);
                });
        });
    });
</script>
