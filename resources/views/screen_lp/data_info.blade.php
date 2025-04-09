    <!-- Stats Section -->
    <section id="stats" class="stats section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="mb-5">
                <h1 class="text-center">INFORMASI DATA</h1>
            </div>
            <div class="row gy-4">

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-house-heart"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="{{ $dataCpbTerverifikasi }}"
                            data-purecounter-duration="1" class="purecounter"></span>
                        <p>Penerima RTLH</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-clipboard-check"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="{{ $dataCpbRumahTidakTerverifikasi }}"
                            data-purecounter-duration="1" class="purecounter"></span>
                        <p>Tidak Terverifikasi</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-building"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="{{ $dataCpbRumahTerdata }}"
                            data-purecounter-duration="1" class="purecounter"></span>
                        <p>Rumah Terdata</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-people"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="{{ $dataCpbTotalTerbantu }}"
                            data-purecounter-duration="1" class="purecounter"></span>
                        <p>Total Terbantu</p>
                    </div>
                </div><!-- End Stats Item -->

            </div>

        </div>

    </section><!-- /Stats Section -->
