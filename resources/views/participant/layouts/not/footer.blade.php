{{-- resources/views/partials/footer.blade.php --}}
<section class="footer bg-white pt-5 pb-4 border-top">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: #2e7d32;">
                    <i class="fas fa-seedling me-2"></i>Green Leadership Indonesia
                </h5>
                <p class="small text-muted">
                    Program pendidikan kepemimpinan berkelanjutan yang dikembangkan oleh Green Leadership Indonesia untuk membangun pemimpin masa depan melalui pembelajaran interaktif dan partisipatif.
                </p>
                <div class="mt-3 d-flex flex-column align-items-start">
                    <img src="{{ asset('gambar/ihigli1.png') }}"
                         alt="GLI Logo"
                         class="w-60 h-auto mb-1" />
                    <span class="small text-muted">Learning Management System</span>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: #2e7d32;">
                    <i class="fas fa-headset me-2"></i>Kontak Kami
                </h5>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        <a href="mailto:info@greenleadership.id" class="text-decoration-none text-muted">info@greenleadership.id</a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        <a href="tel:+622112345678" class="text-decoration-none text-muted">+62 822 4743 1493 (PIC LMS)</a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                        <span class="text-muted">Jl. Palapa XVII No.3 11, RT.11/RW.5, Ps. Minggu, Jakarta Selatan</span>
                    </li>
                    <li class="mt-3">
                        <a href="#" class="btn btn-sm btn-outline-success me-2">
                            <i class="fas fa-question-circle"></i> Bantuan
                        </a>
                        <a href="{{ asset('html/laporan.html') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-bug"></i> Laporkan Masalah
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Social Media & Developed By -->
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: #2e7d32;">
                    <i class="fas fa-users me-2"></i>Terhubung Dengan Kami
                </h5>
                <div class="mb-3">
                    @foreach(['facebook-f','twitter','instagram','youtube','linkedin-in'] as $icon)
                        <a href="#" class="btn btn-sm btn-outline-secondary me-2 mb-2" style="border-color: #4CAF50; color: #4CAF50;">
                            <i class="fab fa-{{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>

                <div class="bg-light p-3 rounded small" style="border-left: 3px solid #4CAF50;">
                    <h6 class="fw-bold mb-2" style="color: #2e7d32;">
                        <i class="fas fa-code me-2"></i>Dikembangkan Oleh
                    </h6>
                    <p class="mb-2 text-muted">
                        Tim IT Green Leadership Indonesia<br />
                        <span class="text-success fw-semibold">Leadership LMS v1.0</span>
                    </p>
                    <p class="mb-0 small text-muted">
                        &copy; 2025 Hak Cipta Dilindungi
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center small text-muted">
                <p class="mb-0">Sistem Pembelajaran Kepemimpinan untuk Membangun Masa Depan Berkelanjutan</p>
            </div>
        </div>
    </div>
</section>
