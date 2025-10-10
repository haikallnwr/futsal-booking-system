@extends('frontend.layouts.main')
@section('container')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center justify-content-center">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
                <div class="col-xl-6 col-lg-8">
                    <h1>Arena Futsal Jakarta<span>.</span></h1>
                    <h2>Tempat Booking Lapangan Futsal Wilayah Jakarta</h2>
                    <a href="{{ url('/gor') }}" class="btn btn-success mt-4">Explore<i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>
    </section><!-- End Hero -->

       <!-- ======= Search Section ======= -->
    <section class="search-section py-4" style="margin-top: -90px; position: relative; z-index: 10;">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form action="{{ route('search') }}" method="GET" 
                          class="search-container px-4 py-5 bg-primary rounded-4 shadow-sm">
                        <div class="row g-3 align-items-center px-3">
                            
                            <!-- Wilayah Selection -->
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-white text-white">
                                        <i class="bx bxs-location-alt-2 text-light bx-sm"></i>
                                    </span>
                                    <select class="form-select bg-transparent border-white text-white custom-select" 
                                            id="search-wilayah" 
                                            name="wilayah">
                                        <option class="rounded-4" value="" disabled selected hidden class="text-dark">Pilih Wilayah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kecamatan Selection -->
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-white text-white">
                                        <i class="bx bxs-location-pin text-light bx-sm"></i>
                                    </span>
                                    <select class="form-select bg-transparent border-white text-white custom-select" 
                                            id="search-kecamatan" 
                                            name="kecamatan" 
                                            >
                                        <option value="" disabled selected hidden class="text-dark">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Jenis Lapangan Selection -->
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-white text-white">
                                        <i class="bx bxs-football-pitch text-light bx-sm"></i>
                                    </span>
                                    <select class="form-select bg-transparent border-white text-white custom-select" 
                                            id="search-jenis-lapangan" 
                                            name="jenis_lapangan">
                                        <option value="" disabled selected hidden class="text-dark">Jenis Lapangan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Search Button -->
                            <div class="col-md-3">
                                <button class="btn btn-dark w-100 d-flex align-items-center justify-content-center text-success rounded-3 py-1 fw-bold" 
                                        type="submit">
                                    Temukan 
                                    <span><i class='bx  bx-arrow-right text-success bx-sm mt-2 ms-3' ></i> </span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section><!-- End Search Section -->

    <main id="main">

        <!-- =======  Section Tentang Kami ======= -->
        <section id="why-us" class=" py-3">
            <div class="container" data-aos="fade-up">
                <div data-aos="fade-up" class="section-title text-center mb-2">
                    <h3 class="fw-bold py-2">KENAPA HARUS PAKAI AFUTA?</h3>
                    <h2>Berbagai keunggulan melakukan booking online lapangan olahraga melalui website AFUTA</h2>
                </div>

                <div class="row g-4 mt-1">
                    <!-- Card 1 -->
                    <div class="col-lg-4">
                        <div data-aos="fade-up" class="p-3 mb-5 bg-white rounded">
                            <div class="mb-3">
                                <i class="bi bi-file-earmark-check text-warning fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Direktori Lapangan Lengkap</h5>
                            <p>
                                Kami bekerja sama dengan berbagai lapangan olahraga seperti lapangan badminton, tenis,
                                basket, voli, futsal, dan sepak bola. Saat ini pusat AFUTA ada di Jakarta, tapi akan
                                merambah ke kota-kota lain di Indonesia.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-lg-4">
                        <div data-aos="fade-up" data-aos-delay="100" class="p-3 mb-5 bg-white rounded">
                            <div class="mb-3">
                                <i class="bi bi-geo-alt text-success fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Dekat dengan Anda</h5>
                            <p>
                                Kami tahu Anda ingin mencari lapangan yang dekat dan sesuai dengan keinginan. Sistem kami
                                mendeteksi lapangan yang ada di sekitar Anda. Bahkan Anda bisa menemukan lapangan baru di
                                daerah Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-lg-4">
                        <div data-aos="fade-up" data-aos-delay="200" class="p-3 mb-5 bg-white rounded">
                            <div class="mb-3">
                                <i class="bi bi-bullseye text-primary fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Jaminan Pemesanan</h5>
                            <p>
                                Kami memastikan deskripsi dan foto lapangan sesuai dengan kenyataan. Jika ada masalah
                                seperti lapangan tidak sesuai, kami akan bantu menyelesaikannya dengan manajemen, termasuk
                                pengembalian dana.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

                <div class="container my-2 mb-5">
                    <div class="row align-items-center justify-content-center">
                        <div data-aos="fade-up" class="col-md-6 mb-4 mb-md-0 position-relative">
                            <img class="shadow-sm rounded-4" src="{{ asset('assets/images/Home1.jpg') }}" alt="Sistem Manajemen"
                                class="img-fluid" style="max-width: 400px;">
                                <i style='font-size:130px; margin-top:55px; margin-left:50px;' class='text-light text-center p-3 bg-primary bx  bxs-gear position-absolute top-0 start-50 rounded-5 shadow-sm'   ></i> 
                        </div>
                        <div data-aos="fade-up" data-aos-delay="200" class="col-md-6">
                            <h2 class="fw-bold">Sistem Manajemen Lapangan Terpadu</h2>
                            <p class="text-muted">
                                Buat Anda pemilik bisnis Lapangan Olahraga, bekerja sama dengan AFUTA akan meningkatkan
                                omzet penghasilan lapangan Anda secara signifikan. Bukan hanya itu saja, sistem manajemen
                                AFUTA akan mencatat setiap transaksi yang masuk dan menampilkan perkembangan pendapatan
                                Anda dalam bentuk grafik.
                            </p>
                        </div>
                    </div>
                </div>

        

                <div class="container">
                    <div class="row g-5 align-items-start mt-1">

                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <h2 class="fw-bold mb-3">Buat Lapanganmu Penuh Terus</h2>
                            <p class="text-muted mb-4">Berikut apa yang akan Anda dapatkan dari partnership AFUTA.</p>
                            <ul class="list-unstyled text-muted mb-4">
                                <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Promosi Online
                                    terhadap calon penyewa potensial</li>
                                <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Tidak perlu repot
                                    terima telp dan chat menanyakan jadwal</li>
                                <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Tidak ada kerugian
                                    akibat pembatalan dari penyewa</li>
                                <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Laporan Pendapatan
                                    Komprehensif</li>
                                <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>Kemudahan mengatur
                                    berbagai Promo</li>
                            </ul>
                            <a href="{{ route('contact.index') }}" class="btn btn-primary rounded-3 px-4 py-2">Ingin menjadi partner kami? silahkan klik disini!</a>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="row g-3">
                                <!-- Card 1 -->
                                <div class="col-sm-6">
                                    <div class="bg-white p-4 rounded-5 shadow-sm h-100">
                                        <div class="icon-box text-warning">🎯</div>
                                        <h5 class="fw-bold">Online Marketing</h5>
                                        <p class="text-muted">Setiap hari ada pengguna baru yang mengetahui lapangan Anda.
                                        </p>
                                    </div>
                                </div>
                                <!-- Card 2 -->
                                <div class="col-sm-6">
                                    <div class="bg-white p-4 rounded-5 shadow-sm h-100">
                                        <div class="icon-box text-success">🕒</div>
                                        <h5 class="fw-bold">Jadwal Online</h5>
                                        <p class="text-muted">Selalu ada pengguna baru yang hendak booking jadwal kosong
                                            lapangan Anda.</p>
                                    </div>
                                </div>
                                <!-- Card 3 -->
                                <div class="col-sm-6">
                                    <div class="bg-white p-4 rounded-5 shadow-sm h-100">
                                        <div class="icon-box text-primary">🎛️</div>
                                        <h5 class="fw-bold">Buat Promo menarik</h5>
                                        <p class="text-muted">Tentukan harga secara bebas, sesuai hari sesuai jam. Buat
                                            promo-promo menarik lainnya sekarang!</p>
                                    </div>
                                </div>
                                <!-- Card 4 -->
                                <div class="col-sm-6">
                                    <div class="bg-white p-4 rounded-5 shadow-sm h-100">
                                        <div class="icon-box text-danger">📄</div>
                                        <h5 class="fw-bold">Laporan Lengkap & Pembayaran Rutin</h5>
                                        <p class="text-muted">Pembayaran rutin harian, mingguan, bulanan tersedia langsung
                                            di AFUTA.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
    </main><!-- End #main -->
    
@endsection
@push('js_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const wilayahSelect = document.getElementById('search-wilayah');
    const kecamatanSelect = document.getElementById('search-kecamatan');
    const jenisLapanganSelect = document.getElementById('search-jenis-lapangan');

    let filterData = {}; // Variabel untuk menyimpan data dari API

    // 1. Ambil data filter dari API saat halaman dimuat
    fetch('/api/search-filters')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            filterData = data; // Simpan data ke variabel global
            
            // 2. Isi dropdown Wilayah
            const wilayahOptions = Object.keys(filterData.locations).sort();
            wilayahOptions.forEach(wilayah => {
                const option = new Option(wilayah, wilayah);
                wilayahSelect.add(option);
            });

            // 3. Isi dropdown Jenis Lapangan
            filterData.field_types.forEach(type => {
                const option = new Option(type, type);
                jenisLapanganSelect.add(option);
            });
        })
        .catch(error => {
            console.error('Error fetching search filters:', error);
            // Anda bisa menampilkan pesan error kepada pengguna di sini jika perlu
        });

    // 4. Tambahkan event listener untuk dropdown Wilayah
    wilayahSelect.addEventListener('change', function() {
        // Kosongkan dan nonaktifkan dropdown kecamatan terlebih dahulu
        kecamatanSelect.innerHTML = '<option value="" disabled selected hidden class="text-dark">Pilih Kecamatan</option>';
        kecamatanSelect.disabled = true;

        const selectedWilayah = this.value;

        if (selectedWilayah && filterData.locations[selectedWilayah]) {
            // Aktifkan dropdown kecamatan
            kecamatanSelect.disabled = false;
            
            // Isi dropdown kecamatan berdasarkan wilayah yang dipilih
            const kecamatanOptions = filterData.locations[selectedWilayah];
            kecamatanOptions.forEach(kecamatan => {
                const option = new Option(kecamatan, kecamatan);
                kecamatanSelect.add(option);
            });
        }
    });
});
</script>
@endpush
